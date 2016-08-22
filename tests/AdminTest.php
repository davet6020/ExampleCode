<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminTest extends TestCase
{
    // /**
    //  * A basic test example.
    //  *
    //  * @return void
    //  */
    // public function testSeeAdminHomeNoSettings()
    // {

    //     // Reset to a factory db
    //     $this->resetDB();
    //     $this->adminLogin();
    //     $this->visit('/admin/settings')
    //         ->see('Admin Settings Home')
    //         ->see('Create Settings')
    //         ->dontSee('Edit Active Settings');
    // }

    public function testSeeAdminHomeWithSettings()
    {
        // Reset to a factory db
        $this->resetDB();
        $this->adminLogin();
        factory(App\Admin\AdminSettings::class)->create();
        $this->visit('/admin/settings')
            ->see('Admin Settings Home')
            ->see('Edit Active Settings')
            ->dontSee('Create Settings');
    }
}
