<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    // Create a User and verify user is in the database
    public function testUserCreateUser()
    {
        // Reset to a factory db
        $this->resetDB();
        $this->adminLogin();

        // Create a user in the UI
        $this->visit('/user/create')
             ->see('First Name')
             ->see('Last Name')
             ->see('Email')
             ->see('Password')
             ->see('Company')
             ->see('Make a user!')
             ->type('John', 'first_name')
             ->type('Doe', 'last_name')
             ->type('testing@303software.com', 'email')
             ->type('123', 'password')
             ->type('303 Software', 'company')
             ->press('Make a user!')
             ->seePageIs('/user');

        // Verify in database
        $this->seeInDatabase('users', [
          'first_name' => 'John',
          'last_name' => 'Doe',
          'email' => 'testing@303software.com',
          'company' => '303 Software',
        ]);
    }

    // Delete a User and verify user is NOT in the database
    public function testUserDeleteUser()
    {
        // Reset to a factory db
        $this->resetDB();
        $this->adminLogin();

        // Create a user in the UI
        $this->visit('/user')
             ->press('delete');

        // Verify in database
        $this->dontSeeInDatabase('users', [
          'email' => 'testing@303software.com',
        ]);
    }

    // Update a User and verify user is updated in the database
    public function testUserUpdateUser()
    {
        // Reset to a factory db
        $this->resetDB();
        $this->adminLogin();

        // Create a user in the UI
        $this->visit('/user')
             ->press('update')
             ->type('Bill', 'first_name')
             ->type('Nye', 'last_name')
             ->type('scienceguy@303software.com', 'email')
             ->type('321', 'password')
             ->type('The Planetary Society', 'company')
             ->press('Update')
             ->seePageIs('/user/1');

        // Verify in database
        $this->seeInDatabase('users', [
          'first_name' => 'Bill',
          'last_name' => 'Nye',
          'email' => 'scienceguy@303software.com',
          'company' => 'The Planetary Society',
        ]);

    }

    public function testUserListingPage()
    {
        $this->resetDB();
        $this->adminLogin();

        $this->visit('/user')
             ->see('List of all users');
    }

    // Create a User and verify user is in the database via the Register route
    public function testRegisterCreateUser()    {
        // Reset to a factory db
        $this->artisan('migrate:reset');
        $this->artisan('migrate');

        // Create a user in the UI
        $this->visit('/register/create')
              ->see('First Name')
              ->see('Last Name')
              ->see('Email')
              ->see('Password')
              ->see('Company')
              ->see('Create My Account')
              ->type('Angus', 'first_name')
              ->type('Young', 'last_name')
              ->type('dave@303software.com', 'email')
              ->type('123', 'password')
              ->type('303 Software', 'company')
              ->press('Create My Account')
              ->seePageIs('/register');

        // Verify in database
        $this->seeInDatabase('users', [
            'first_name' => 'Angus',
            'last_name' => 'Young',
            'email' => 'dave@303software.com',
            'company' => '303 Software',
        ]);
    }

    // Test register/login with the user created in testRegisterCreateUser()
    public function testRegisterLogin()    {
        // Reset to a factory db
        /*$this->artisan('migrate:reset');
        $this->artisan('migrate');*/

        // Create a user in the UI
        $this->visit('/register/login')
              ->see('Email')
              ->see('Password')
              ->see('Login')
              ->type('dave@303software.com', 'email')
              ->type('123', 'password')
              ->press('Login')
              ->seePageIs('/register/login');
    }

    public function testUpdateProfile() {
        // User 1 should exist still so update the profile
        $this->visit('/register/1/edit')
              ->see('First Name')
              ->see('Last Name')
              ->see('Email')
              ->see('Password')
              ->see('Company')
              ->see('Cell Phone')
              ->see('Home Phone')
              ->see('Work Phone')
              ->see('Billing Address')
              ->see('Type')
              ->see('Full_name')
              ->see('Name_on_cc')
              ->see('Address_1')
              ->see('Address_2')
              ->see('City')
              ->see('State')
              ->see('Zip')
              ->see('Country')
              ->see('Company Address')
              ->see('Type')
              ->see('Full_name')
              ->see('Name_on_cc')
              ->see('Address_1')
              ->see('Address_2')
              ->see('City')
              ->see('State')
              ->see('Zip')
              ->see('Country')
              ->see('Shipping Address')
              ->see('Type')
              ->see('Full_name')
              ->see('Name_on_cc')
              ->see('Address_1')
              ->see('Address_2')
              ->see('City')
              ->see('State')
              ->see('Zip')
              ->see('Country')
              ->type('password', 'password')
              ->type('720-001-0001', 'cell_phone')
              ->type('720-001-0002', 'home_phone')
              ->type('720-001-0003', 'work_phone')
              ->type('Billing', 'billing_type')
              ->type('billing_full_name', 'billing_full_name')
              ->type('billing_name_on_cc', 'billing_name_on_cc')
              ->type('billing_address_1', 'billing_address_1')
              ->type('billing_address_2', 'billing_address_2')
              ->type('billing_city', 'billing_city')
              ->type('billing_state', 'billing_state')
              ->type('billing_zip', 'billing_zip')
              ->type('billing_country', 'billing_country')
              ->type('Company', 'company_type')
              ->type('company_full_name', 'company_full_name')
              ->type('company_name_on_cc', 'company_name_on_cc')
              ->type('company_address_1', 'company_address_1')
              ->type('company_address_2', 'company_address_2')
              ->type('company_city', 'company_city')
              ->type('company_state', 'company_state')
              ->type('company_zip', 'company_zip')
              ->type('company_country', 'company_country')
              ->type('Shipping', 'shipping_type')
              ->type('shipping_full_name', 'shipping_full_name')
              ->type('shipping_name_on_cc', 'shipping_name_on_cc')
              ->type('shipping_address_1', 'shipping_address_1')
              ->type('shipping_address_2', 'shipping_address_2')
              ->type('shipping_city', 'shipping_city')
              ->type('shipping_state', 'shipping_state')
              ->type('shipping_zip', 'shipping_zip')
              ->type('shipping_country', 'shipping_country')
              ->press('Update')
              ->seePageIs('/register/1');
    }

}