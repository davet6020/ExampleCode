<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Mail;
use Illuminate\Support\Facades\Validator;
use App\Users\CovUser;
use App\Address;

class UsersController extends Controller
{

    /**
     * constructor
     * @param Request $request Laravel Request object
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->user = \Request::get('active_user');
    }

    /**
     * API endpoint for login. Expects email and password
     * @return Response json response
     */
    public function login()
    {
        $credentials = [
          'email'    => $this->request->get('email'),
          'password' => $this->request->get('password'),
        ];

        $user = \Sentinel::authenticate($credentials);

        if ($user) {
            $userAdd = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'name' => $user->first_name . ' ' . $user->last_name,
                'admin' => $user->hasAccess('admin')
            ];

            $token = \JWTAuth::fromUser($user, $userAdd);
            $this->viewVars['token'] = $token;
            return $this->json();
        } else {
            return $this->abort('auth.failed', 401);
        }
    }

    /**
     * API Endpoint for requesting a forgotten password email
     * @return Response json response
     */
    public function forgotPassword()
    {
        $credentials = [
          'email'    => $this->request->get('email'),
        ];

        $user = \Sentinel::findByCredentials($credentials);
        if (empty($user)) {
            $this->success('passwords.sent');
            return $this->json();
        }

        $mailer = new Mail\Mail();

        // if the user is not activated, resend the activation email
        if (!\Activation::completed($user)) {
            $activation = \Activation::exists($user);
            $mailer->activationEmail($user, $activation->code);
            $this->warning('auth.resendActivation');
            return $this->json(400);
        }

        // The user is active, and wants to reset the password, use Sentinel Reminders
        $reminder = \Reminder::create($user);
        $mailer->resetPasswordEmail($user, $reminder->code);

        $this->success('passwords.sent');
        return $this->json();
    }

    /**
     * API Endpoint for setting a password for forgotten passwords
     * @return Response json response
     */
    public function changePassword()
    {
        $user = \Sentinel::findById(\Request::get('id'));

        if (\Reminder::complete($user, \Request::get('code'), \Request::get('password'))) {
            $this->success('passwords.reset');
            return $this->json();
        } else {
            return $this->abort('passwords.fail');
        }
    }

    /**
     * List User(s). Will not show any other user to non admin users
     * @param  integer $id ID of user to show
     * @return Response    json response
     */
    public function apiFind($id = null)
    {
        if ($this->user->hasAccess('admin') && empty($id)) {
            $users = $this->getPagination(CovUser::select())->get();
        } else {
            if (empty($id)) {
                $id = $this->user->id;
            }
            $users = CovUser::with('address')->where('id', $id)->get()->toArray();
        }

        $this->viewVars['data'] = $this->sanitizeUsers($users);
        return $this->json();
    }

    /**
     * API Endpoint for creating a user
     * @return Response json response
     */
    public function apiCreate()
    {
        if (!$this->user->hasAccess('admin')) {
            return $this->abort('auth.denied');
        }
        $inputs = \Request::all();

        // Check to see if this user exists already.
        $credentials = [
          'email' => $this->request->get('email'),
        ];
        $user = \Sentinel::findByCredentials($credentials);

        if (isset($user)) {
            return $this->abort('errors.existingEmail');
        } else {
            try {
                $userCreated = \Sentinel::register($inputs);
                $this->syncAddresses($userCreated, request()->input('address'));

                $activation = \Activation::create($userCreated);

                $mailer = new Mail\Mail();

                $mailer->activationEmail($userCreated, $activation['code']);
                $this->success('auth.activation');
                return $this->json();
            } catch (Exception $ex) {
                return $this->abort('Unexpected Server Error.', 500);
            }
        }
    }

    /**
     * API endpoint for updating a user
     * @param  string $id User ID
     * @return array      JSON return statement
     */
    public function apiUpdate($id)
    {
        $id = intval($id);
        if ($id !== $this->user->id && !$this->user->hasAccess('admin')) {
            return $this->abort('auth.denied');
        }

        $user = \Sentinel::findById($id);

        $credentials = [
          'first_name' => request()->input('first_name'),
          'last_name' => request()->input('last_name'),
          'email' => request()->input('email'),
          'company' => request()->input('company'),
          'has_terms' => request()->input('has_terms'),
          'account_number' => request()->input('account_number')
        ];

        if (!empty(\Request::get('password'))) {
            $credentials['password'] = $this->request->get('password');
        }
        $user = \Sentinel::update($user, $credentials);

        $this->syncAddresses($user, request()->input('address'));

        $user = CovUser::with('address')->where('id', $id)->get()->toArray();
        if ($user) {
            $this->viewVars['data'] = $this->sanitizeUser($user);
            return $this->json();
        } else {
            $this->abort('Unable to update this user.');
        }
    }

    /**
     * API endpoint for deleting a user
     * @param  string $id User ID
     * @return array      JSON Response
     */
    public function apiDelete($id)
    {
        if (!$this->user->hasAccess('admin')) {
            return $this->abort('auth.denied');
        }

        $user = \Sentinel::findById($id);
        if ($user->delete()) {
            $this->success(sprintf('%s %s Deleted.', $user->first_name, $user->last_name));
        }

        return $this->json();
    }

    public function apiLogout()
    {
        \JWTAuth::invalidate();

        $this->success('auth.logout');
        return $this->json();
    }

    /**
     * Clear any properties from a user that should not be sent over the wire
     * @param  Object $user The User to sanitize
     * @return Object       Cleaned user
     */
    protected function sanitizeUser($user)
    {
        unset($user['permissions']);
        unset($user['last_login']);
        unset($user['created_at']);
        unset($user['updated_at']);
        unset($user['created_by']);

        if (!$this->user->hasAccess('admin')) {
            unset($user['has_terms']);
            unset($user['account_number']);
            unset($user['company_account']);
        }
        return $user;
    }

    /**
     * Sanitize multiple users
     * @param  Array $users Array of Users
     * @return Array        Cleaned Users
     */
    protected function sanitizeUsers($users)
    {
        foreach ($users as $key => $user) {
            $users[$key] = $this->sanitizeUser($user);
        }
        return $users;
    }

    /**
     * Syncronize addresses for a user
     * @param  CovUser $user      User object
     * @param  Array  $addresses Array of Addresses
     * @return bool
     */
    protected function syncAddresses($user, $addresses = [])
    {
        if (empty($addresses)) {
            return;
        }

        $newAddresses = [];
        foreach ($addresses as $address) {
            if (!empty($address['id'])) {
                $model = Address::find($address['id']);
            } else {
                $model = new \App\Address();
            }
            $model->fill($address);
            $model->user_id = $user->id;

            $model->save();
            $newAddresses[] = $model->id;
        }

        Address::where('user_id', $user->id)->whereNotIn('id', $newAddresses)->delete();
        return true;
    }

    // // Pre angular stuff
    // public function create() {
    //     return $this->render('admin/user/create');
    // }

    // public function destroy($id) {
    //     $user = \Sentinel::findById($id);
    //     $user->delete();

    //     return redirect()->action('UsersController@index');
    // }

    // public function edit($id) {
    //     $user = User::find($id);
    //     unset($user->password);

    //     $this->viewVars['user'] = $user;
    //     return $this->render('admin/user/update');
    // }

    // public function find()  {
    //     return $this->render('admin/user/find');
    // }

    // public function findByName()  {
    //     $inputs['email'] = $this->request->get('email');

    //     $credentials = [
    //       'login' => $inputs['email'],
    //     ];

    //     $user = \Sentinel::findByCredentials($credentials);

    //     if($user) {
    //         return redirect()->action('UsersController@edit', ['id' => $user['id']]);
    //     } else  {
    //         return redirect()->back()->with('message', 'That user was not found.');
    //     }
    // }

    // public function index() {
    //     $users = User::all();
    //     return view('admin/user/index', ['users' => $users]);
    // }

    // public function loginForm()  {
    //     return $this->render('admin/user/login');
    // }

    // public function loginTest()  {
    //     $credentials = [
    //       'email'    => $this->request->get('email'),
    //       'password' => $this->request->get('password'),
    //     ];

    //     $user = \Sentinel::authenticate($credentials);

    //     if ($user = \Sentinel::check())  {
    //         unset($user->password);
    //         $this->viewVars['email'] = $user['email'];
    //         $this->viewVars['id'] = $user['id'];

    //         return $this->render('admin/user/loggedin');
    //     } else  {
    //         echo "You are not logged in.";
    //     }
    // }

    // public function logout()  {
    //     \Sentinel::logout();

    //     return $this->render('admin/user/logout');
    // }

    // public function show($id)  {
    //     $user = User::find($id);

    //     $this->viewVars['user'] = $user;
    //     return $this->render('admin/user/show');
    // }

    // public function store() {
    //     $inputs['first_name'] = $this->request->get('first_name');
    //     $inputs['last_name'] = $this->request->get('last_name');
    //     $inputs['email'] = $this->request->get('email');
    //     $inputs['password'] = $this->request->get('password');
    //     $inputs['company'] = $this->request->get('company');
    //     $inputs['has_terms'] = $this->request->get('has_terms');
    //     $inputs['account_number'] = $this->request->get('account_number');

    //     // Check to see if this user exists already.
    //     $credentials = [
    //       'email' => $this->request->get('email'),
    //     ];
    //     $user = \Sentinel::findByCredentials($credentials);

    //     if(isset($user))  {
    //         $this->viewVars['message'] = 'The user you are attempting to create: ' . $credentials['email'] . ' already exists';

    //         return $this->render('admin/user/create');
    //     } else  {
    //         $userCreated = \Sentinel::register($inputs);
    //         //$userCreated = \Sentinel::registerAndActivate($inputs);
    //         $activation = \Activation::create($userCreated);
    //         $this->viewVars['email'] = $userCreated['email'];

    //         // Create the activation url and email it to the user.
    //         /*$uri = '/register/' . 'pwsetnew/' . $userCreated['id'] . '/' . $activation['code'];
    //         $activationUrl = url($uri);*/
    //         $uri = '/user/' . 'activation/' . $userCreated['id'] . '/' . $activation['code'];
    //         $activationUrl = url($uri);

    //         $this->activationEmail($activationUrl, $userCreated['id']);
    //         $this->viewVars['msg'] = "The user was sent an email with activation instructions.";

    //         return $this->render('emails/test');
    //         //return $this->render('admin/user/activated');
    //     }
    // }

    // public function update($id) {
    //     $user = \Sentinel::findById($id);

    //     $credentials = [
    //       'first_name' => $this->request->get('first_name'),
    //       'last_name' => $this->request->get('last_name'),
    //       'email' => $this->request->get('email'),
    //       'company' => $this->request->get('company'),
    //       'user_type' => $this->request->get('user_type'),
    //       'has_terms' => $this->request->get('has_terms'),
    //       'account_number' => $this->request->get('account_number')
    //     ];

    //     if(! empty($this->request->get('password')))  {
    //         $credentials['password'] = $this->request->get('password');
    //     }

    //     if(\Sentinel::update($user, $credentials)) {
    //         return redirect()->action('UsersController@show', ['id' => $id]);
    //     } else  {
    //         return redirect()->action('UsersController@edit', ['id' => $id]);
    //     }
    // }
}
