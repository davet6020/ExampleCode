<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Users\CovUser;
use App\Mail;

class RegisterController extends Controller
{

    public function activationClick($id, $confirmationCode)
    {
        $user = \Sentinel::findUserById($id);

        if ($activation = \Activation::completed($user)) {
            // User has completed the activation process
            return redirect()->to('/')->with('error', 'This account is already activated.');
        } else {
            // Activation not found or not completed
            if (\Activation::complete($user, $confirmationCode)) {
                // Activation was successful
                return redirect()->to('/')->with('success', 'auth.activationSuccess');
            } else {
                // Activation not found or not completed.
                return redirect()->to('/')->with('error', 'There was a problem registering your account.');
            }
        }
    }

    public function register() {
        // $inputs['first_name'] = $this->request->get('first_name');
        // $inputs['last_name'] = $this->request->get('last_name');
        // $inputs['email'] = $this->request->get('email');
        // $inputs['password'] = $this->request->get('password');
        // $inputs['company'] = $this->request->get('company');

        $inputs = \Request::all();
        $mailer = new Mail\Mail();

        // Check if we already have an email registered
        $existingUser = \Sentinel::findByCredentials(['email' => $inputs['email']]);
        if ($existingUser) {
            // Check if they are not activated, send activation email again
            if (!\Activation::completed($existingUser)) {
                // @TODO Update user profile with the new information
                $activation = \Activation::exists($existingUser);
                $mailer->activationEmail($existingUser, $activation['code']);
                $this->success('auth.activation');
                return $this->json();
            }
            return $this->abort('errors.existingEmail');
        }

        $userCreated = \Sentinel::register($inputs);
        $activation = \Activation::create($userCreated);

        $mailer->activationEmail($userCreated, $activation['code']);
        $this->success('auth.activation');
        return $this->json(200);
    }

    public function forgotPassword() {
        $credentials = [
          'email'    => $this->request->get('email'),
        ];

        $user = \Sentinel::findByCredentials($credentials);

        if($activation = \Activation::completed($user)) {
            // User is activated so create the activation url and email it to the user.
            $uri = '/register/' . 'pwreset/' . $user['id'] . '/' . $activation['code'];
            $activationUrl = url($uri);

            $this->pwResetEmail($activationUrl, $user['id']);
            $this->viewVars['msg'] = "An email was sent to your mailbox with password reset instructions.";
        } else  {
            // User not activated yet
            $activation = \Activation::exists($user);
            // Create the activation url and email it to the user.
            $uri = '/register/' . 'activation/' . $user['id'] . '/' . $activation['code'];
            $activationUrl = url($uri);

            $this->activationEmail($activationUrl, $user['id']);
            $this->viewVars['msg'] = "An email was sent to your mailbox with activation instructions.";
        }
        return $this->render('emails/test');
    }

    public function create()    {
        return $this->render('register/create');
    }

    public function destroy($id) {
        $user = \Sentinel::findById($id);

        // Destroy all sessions for this user prior to deletion.
        $user = \Sentinel::findUserById($id);
        \Sentinel::logout($user, true);

        $user->delete();

        return redirect()->action('RegisterController@index');
    }

    public function edit($id) {
        $user = CovUser::with('Address')->find($id);
        unset($user->password);
        $this->viewVars['user'] = $user;

        foreach($user->address as $address) {
            if($address->type == 'Billing') {
              $this->viewVars['billing'] = $address->toArray();
            }
            if($address->type == 'Shipping') {
              $this->viewVars['shipping'] = $address->toArray();
            }
            if($address->type == 'Company') {
              $this->viewVars['company'] = $address->toArray();
            }
        }

        return $this->render('register/update');
    }

    public function index() {
        return $this->render('register/create');
    }

    public function loginForm()  {
        $uri = '/register/pwforgot/';
        $forgotUrl = url($uri);
        $this->viewVars['forgotUrl'] = $forgotUrl;

        return $this->render('register/login');
    }

    public function login()  {
        \Sentinel::logout();
        $credentials = [
          'email'    => $this->request->get('email'),
          'password' => $this->request->get('password'),
        ];

        // Get user object
        $user = \Sentinel::findByCredentials($credentials);

        if(! isset($user))  {
            $this->viewVars['email'] = $this->request->get('email');
            return $this->render('register/notloggedin');
        }

        // If user is activated
        if ($activation = \Activation::completed($user)) {
            // If user is logged in already
            if ($user = \Sentinel::check())  {
                unset($user->password);
                $this->viewVars['email'] = $user['email'];
                $this->viewVars['id'] = $user['id'];
                return $this->render('register/loggedin');
            } else  {
                // Try to log them in
                if($user = \Sentinel::authenticate($credentials)) {
                    $this->viewVars['email'] = $user['email'];
                    $this->viewVars['id'] = $user['id'];
                    return $this->render('register/loggedin');
                } else  {
                    // Probably username or password is incorrect.
                    $this->viewVars['email'] = $user['email'];
                    return $this->render('register/notloggedin');
                }
            }
        } else  {
            // Activation not found or not completed
            $this->viewVars['email'] = $this->request->get('email');
            return $this->render('register/notloggedin');
        }
    }

    public function logout()  {
        \Sentinel::logout();

        return $this->render('register/logout');
    }

    /*Once pwforgot.blade posts, it goes here to send a reset link to the users email*/
    public function pwForgot()   {
        $credentials = [
          'email'    => $this->request->get('email'),
        ];

        $user = \Sentinel::findByCredentials($credentials);


        if($activation = \Activation::completed($user)) {
            // User is activated so create the activation url and email it to the user.
            $uri = '/register/' . 'pwreset/' . $user['id'] . '/' . $activation['code'];
            $activationUrl = url($uri);

            $this->pwResetEmail($activationUrl, $user['id']);
            $this->viewVars['msg'] = "An email was sent to your mailbox with password reset instructions.";
        } else  {
            // User not activated yet
            $activation = \Activation::exists($user);
            // Create the activation url and email it to the user.
            $uri = '/register/' . 'activation/' . $user['id'] . '/' . $activation['code'];
            $activationUrl = url($uri);

            $this->activationEmail($activationUrl, $user['id']);
            $this->viewVars['msg'] = "An email was sent to your mailbox with activation instructions.";
        }
        return $this->render('emails/test');
    }

    /*This happens when the user received the password reset url in their email and they clicked it*/
    public function pwReset($id, $activationUrl) {
        $user = User::find($id);
        $this->viewVars['id'] = $user['id'];

        return $this->render('register/pwsetnew');
    }

    public function pwResetEmail($activationUrl, $id) {
        $user = User::find($id);
        $body = 'Click on this link to reset your password.' . $activationUrl;
        $fullName = $user['first_name'] . ' ' . $user['last_name'];
        $user->name = $fullName;

        \Mail::send('emails.send', ['user' => $user, 'body' => $body], function ($m) use ($user) {
            $m->from(env('MAIL_FROM_USER'), 'Password Reset');

            $m->to($user->email, $user->name)->subject('Password Reset');
        });
    }

    public function pwSetNew()  {
        $inputs['password'] = $this->request->get('password');
        $inputs['id'] = $this->request->get('id');
        $user = \Sentinel::findById($inputs['id']);

        \Sentinel::update($user, array('password' => $inputs['password']));

        $uri = '/register/pwforgot/';
        $forgotUrl = url($uri);
        $this->viewVars['forgotUrl'] = $forgotUrl;

        return $this->render('register/login');
    }

    public function show($id)  {
        $user = User::find($id);
        $this->viewVars['user'] = $user;

        $match = ['user_id' => $user->id, 'type' => 'Billing'];
        $this->viewVars['billing'] = \App\Address::where($match)->first();

        $match = ['user_id' => $user->id, 'type' => 'Company'];
        $this->viewVars['company'] = \App\Address::where($match)->first();

        $match = ['user_id' => $user->id, 'type' => 'Shipping'];
        $this->viewVars['shipping'] = \App\Address::where($match)->first();

        return $this->render('register/show');
    }

    public function store() {
        $inputs['first_name'] = $this->request->get('first_name');
        $inputs['last_name'] = $this->request->get('last_name');
        $inputs['email'] = $this->request->get('email');
        $inputs['password'] = $this->request->get('password');
        $inputs['company'] = $this->request->get('company');

        $userCreated = \Sentinel::register($inputs);
        $activation = \Activation::create($userCreated);

        // Create the activation url and email it to the user.
        $uri = '/register/' . 'activation/' . $userCreated['id'] . '/' . $activation['code'];
        $activationUrl = url($uri);

        $this->activationEmail($activationUrl, $userCreated['id']);
        $this->viewVars['msg'] = "An email was sent to your mailbox with activation instructions.";
        return $this->render('emails/test');
    }

    public function update($id) {
        $user = \Sentinel::findById($id);

        $credentials = [
          'email' => $this->request->get('email'),
          'company' => $this->request->get('company'),
          'cell_phone' => $this->request->get('cell_phone'),
          'home_phone' => $this->request->get('home_phone'),
          'work_phone' => $this->request->get('work_phone'),
          'first_name' => $this->request->get('first_name'),
          'last_name' => $this->request->get('last_name'),
        ];

        $billing = [
          'type' => $this->request->get('billing_type'),
          'full_name' => $this->request->get('billing_full_name'),
          'name_on_cc' => $this->request->get('billing_name_on_cc'),
          'address_1' => $this->request->get('billing_address_1'),
          'address_2' => $this->request->get('billing_address_2'),
          'city' => $this->request->get('billing_city'),
          'state' => $this->request->get('billing_state'),
          'zip' => $this->request->get('billing_zip'),
          'country' => $this->request->get('billing_country'),
          'user_id' => $user->id,
        ];

        $shipping = [
          'type' => $this->request->get('shipping_type'),
          'full_name' => $this->request->get('shipping_full_name'),
          'name_on_cc' => $this->request->get('shipping_name_on_cc'),
          'address_1' => $this->request->get('shipping_address_1'),
          'address_2' => $this->request->get('shipping_address_2'),
          'city' => $this->request->get('shipping_city'),
          'state' => $this->request->get('shipping_state'),
          'zip' => $this->request->get('shipping_zip'),
          'country' => $this->request->get('shipping_country'),
          'user_id' => $user->id,
        ];

        $company = [
          'type' => $this->request->get('company_type'),
          'full_name' => $this->request->get('company_full_name'),
          'name_on_cc' => $this->request->get('company_name_on_cc'),
          'address_1' => $this->request->get('company_address_1'),
          'address_2' => $this->request->get('company_address_2'),
          'city' => $this->request->get('company_city'),
          'state' => $this->request->get('company_state'),
          'zip' => $this->request->get('company_zip'),
          'country' => $this->request->get('company_country'),
          'user_id' => $user->id,
        ];

        // If a Billing record already exists grab it and update it.
        $match = ['user_id' => $user->id, 'type' => 'Billing'];
        $billingAddress = \App\Address::where($match)->first();

        // If a Billing record does not exist create an empty object.
        if(count($billingAddress) == 0)  {
            $billingAddress = new \App\Address;
        }

        // Update the Billing object with the fields from the form and save it.
        if(!empty($this->request->get('billing_address_1'))) {
            $billingAddress->type = $this->request->get('billing_type');
            $billingAddress->full_name = $this->request->get('billing_full_name');
            $billingAddress->name_on_cc = $this->request->get('billing_name_on_cc');
            $billingAddress->address_1 = $this->request->get('billing_address_1');
            $billingAddress->address_2 = $this->request->get('billing_address_2');
            $billingAddress->city = $this->request->get('billing_city');
            $billingAddress->state = $this->request->get('billing_state');
            $billingAddress->zip = $this->request->get('billing_zip');
            $billingAddress->country = $this->request->get('billing_country');
            $billingAddress->user_id = $user->id;
            $billingAddress->save();
        }


        // If a Company record already exists grab it and update it.
        $match = ['user_id' => $user->id, 'type' => 'Company'];
        $companyAddress = \App\Address::where($match)->first();

        // If a Company record does not exist create an empty object.
        if(count($companyAddress) == 0)  {
            $companyAddress = new \App\Address;
        }

        // Update the Company object with the fields from the form and save it.
        if(!empty($this->request->get('company_address_1'))) {
            $companyAddress->type = $this->request->get('company_type');
            $companyAddress->full_name = $this->request->get('company_full_name');
            $companyAddress->name_on_cc = $this->request->get('company_name_on_cc');
            $companyAddress->address_1 = $this->request->get('company_address_1');
            $companyAddress->address_2 = $this->request->get('company_address_2');
            $companyAddress->city = $this->request->get('company_city');
            $companyAddress->state = $this->request->get('company_state');
            $companyAddress->zip = $this->request->get('company_zip');
            $companyAddress->country = $this->request->get('company_country');
            $companyAddress->user_id = $user->id;
            $companyAddress->save();
        }


        // If a Shipping record already exists grab it and update it.
        $match = ['user_id' => $user->id, 'type' => 'Shipping'];
        $shippingAddress = \App\Address::where($match)->first();

        // If a Shipping record does not exist create an empty object.
        if(count($shippingAddress) == 0)  {
            $shippingAddress = new \App\Address;
        }

        // Update the Shipping object with the fields from the form and save it.
        if(!empty($this->request->get('shipping_address_1'))) {
            $shippingAddress->type = $this->request->get('shipping_type');
            $shippingAddress->full_name = $this->request->get('shipping_full_name');
            $shippingAddress->name_on_cc = $this->request->get('shipping_name_on_cc');
            $shippingAddress->address_1 = $this->request->get('shipping_address_1');
            $shippingAddress->address_2 = $this->request->get('shipping_address_2');
            $shippingAddress->city = $this->request->get('shipping_city');
            $shippingAddress->state = $this->request->get('shipping_state');
            $shippingAddress->zip = $this->request->get('shipping_zip');
            $shippingAddress->country = $this->request->get('shipping_country');
            $shippingAddress->user_id = $user->id;
            $shippingAddress->save();
        }

        // Sentinel handles the credentials change.  Mainly the password if it is updated.
        if(! empty($this->request->get('password')))  {
            $credentials['password'] = $this->request->get('password');
        }

        if(\Sentinel::update($user, $credentials)) {
            return redirect()->action('RegisterController@show', ['id' => $id]);
        } else  {
            return redirect()->action('RegisterController@edit', ['id' => $id]);
        }
    }


}
