<?php

use App\Users\Covuser;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function adminLogin() {
        $user = Covuser::where('first_name', 'Admin')->first();
        if ($user) {
            Sentinel::login($user);
        }
    }

    protected function resetDB() {
        $this->artisan('migrate:reset');
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }
}
