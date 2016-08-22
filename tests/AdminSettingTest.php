<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class AdminSettingTest
 */
class AdminSettingTest extends TestCase
{
    /**
    //  * Test settings creation with all data correct and complete.
    //  *
    //  * @return void
    //  */
    public function testCreateWithFullInput()
    {
        // Reset to a factory db
        $this->resetDB();
        $this->adminLogin();
        $this->visit('admin/settings/create')
            ->see('Create quote function')
            ->see('Back to index')
            ->type(10, 'quantity_default')
            ->type(100, 'bom_line_default')
            ->type('.75', 'bom_line_charge_per')
            ->type(150, 'smt_default')
            ->type('.25', 'smt_charge_per')
            ->type(10, 'tht_default')
            ->type(0, 'tht_charge_per')
            ->type(3, 'bga_default')
            ->type('1.50', 'bga_charge_per')
            ->type(2, 'sides_default')
            ->type(75, 'sides_charge_per')
            ->type(1, 'flat_fee_default')
            ->type(200, 'flat_fee_charge_per')
            ->type('1.25', 'function_x')
            ->type('0.05', 'function_b')
            ->type(2, '2_layer_sq_inch_price')
            ->type(600, '2_layer_base_price')
            ->type(3, '4_layer_sq_inch_price')
            ->type(700, '4_layer_base_price')
            ->type(4, '6_layer_sq_inch_price')
            ->type(900, '6_layer_base_price')
            ->type(5, '8_layer_sq_inch_price')
            ->type(1000, '8_layer_base_price')
            ->type(100, 'assembled_turn_day_1')
            ->type(90, 'assembled_turn_day_2')
            ->type(85, 'assembled_turn_day_3')
            ->type(80, 'assembled_turn_day_4')
            ->type(75, 'assembled_turn_day_5')
            ->type(100, 'unassembled_turn_day_1')
            ->type(90, 'unassembled_turn_day_2')
            ->type(85, 'unassembled_turn_day_3')
            ->type(80, 'unassembled_turn_day_4')
            ->type(75, 'unassembled_turn_day_5')
            ->press('submitQuoteForm')
            ->seePageIs('/admin/settings')
            ->see('Edit Active Settings')
            ->seeInDatabase('admin_settings', ['quantity_default' => '10']);
    }

    /**
     * Test settings creation with incomplete data.
     *
     * @return void
     */
    public function testCreateWithIncompleteAndIncorrectInput()
    {
        // Reset to a factory db
        $this->resetDB();
        $this->adminLogin();
        $this->visit('admin/settings/create')
            ->see('Create quote function')
            ->see('Back to index')
            ->type(1.5, 'quantity_default')
            ->type('F', 'bom_line_charge_per')
            ->type(150, 'smt_default')
            ->type('.25', 'smt_charge_per')
            ->type(10, 'tht_default')
            ->type(0, 'tht_charge_per')
            ->type(3, 'bga_default')
            ->type('1.50', 'bga_charge_per')
            ->type(2, 'sides_default')
            ->type(75, 'sides_charge_per')
            ->type(1, 'flat_fee_default')
            ->type(200, 'flat_fee_charge_per')
            ->type('1.25', 'function_x')
            ->type('0.05', 'function_b')
            ->type(2, '2_layer_sq_inch_price')
            ->type(600, '2_layer_base_price')
            ->type(3, '4_layer_sq_inch_price')
            ->type(700, '4_layer_base_price')
            ->type(4, '6_layer_sq_inch_price')
            ->type(900, '6_layer_base_price')
            ->type(5, '8_layer_sq_inch_price')
            ->type(1000, '8_layer_base_price')
            ->type(100, 'assembled_turn_day_1')
            ->type(90, 'assembled_turn_day_2')
            ->type(85, 'assembled_turn_day_3')
            ->type(80, 'assembled_turn_day_4')
            ->type(75, 'assembled_turn_day_5')
            ->type(100, 'unassembled_turn_day_1')
            ->type(90, 'unassembled_turn_day_2')
            ->type(85, 'unassembled_turn_day_3')
            ->type(80, 'unassembled_turn_day_4')
            ->type(75, 'unassembled_turn_day_5')
            ->press('submitQuoteForm')
            ->seePageIs('/admin/settings/create')
            ->see('Could not save settings')
            ->see('The bom line default field is required.')
            ->see('The bom line charge per must be a number.')
            ->see('The quantity default must be an integer.')
            ->dontSeeInDatabase('admin_settings', ['quantity_default' => '10']);
    }

    /**
     * Test settings creation with correct data types.
     *
     * @return void
     */
    public function testEditActiveWithCorrectInput()
    {
        // Reset to a factory db
        $this->resetDB();
        $this->adminLogin();
        factory(App\Admin\AdminSettings::class)->create();
        $this->visit('admin/settings/1/edit')
            ->see('Edit active quote function')
            ->see('Back to index')
            ->type('10', 'quantity_default')
            ->type(100, 'bom_line_default')
            ->type('1.75', 'bom_line_charge_per')
            ->type(150, 'smt_default')
            ->type('.25', 'smt_charge_per')
            ->type(10, 'tht_default')
            ->type(0, 'tht_charge_per')
            ->type(3, 'bga_default')
            ->type('1.50', 'bga_charge_per')
            ->type(2, 'sides_default')
            ->type(75, 'sides_charge_per')
            ->type(1, 'flat_fee_default')
            ->type(200, 'flat_fee_charge_per')
            ->type('1.25', 'function_x')
            ->type('0.05', 'function_b')
            ->type(2, '2_layer_sq_inch_price')
            ->type(600, '2_layer_base_price')
            ->type(3, '4_layer_sq_inch_price')
            ->type(700, '4_layer_base_price')
            ->type(4, '6_layer_sq_inch_price')
            ->type(900, '6_layer_base_price')
            ->type(5, '8_layer_sq_inch_price')
            ->type(1000, '8_layer_base_price')
            ->type(100, 'assembled_turn_day_1')
            ->type(90, 'assembled_turn_day_2')
            ->type(85, 'assembled_turn_day_3')
            ->type(80, 'assembled_turn_day_4')
            ->type(75, 'assembled_turn_day_5')
            ->type(100, 'unassembled_turn_day_1')
            ->type(90, 'unassembled_turn_day_2')
            ->type(85, 'unassembled_turn_day_3')
            ->type(80, 'unassembled_turn_day_4')
            ->type(75, 'unassembled_turn_day_5')
            ->press('submitQuoteForm')
            ->seePageIs('/admin/settings/3/edit')
            ->see('Edit active quote function')
            ->seeInDatabase('admin_settings', ['bom_line_charge_per' => '1.75'])
            ->seeInDatabase('admin_settings', ['active' => '0'])
            ->seeInDatabase('admin_settings', ['active' => '1']);
    }

    /**
     * Test settings creation with incorrect data types.
     *
     * @return void
     */
    public function testEditActiveWithIncorrectInput()
    {
        // Reset to a factory db
        $this->resetDB();
        $this->adminLogin();
        factory(App\Admin\AdminSettings::class)->create();
        $this->visit('admin/settings/1/edit')
            ->see('Edit active quote function')
            ->see('Back to index')
            ->type('1.5', 'quantity_default')
            ->type('', 'bom_line_default')
            ->type('F', 'bom_line_charge_per')
            ->type(150, 'smt_default')
            ->type('.25', 'smt_charge_per')
            ->type(10, 'tht_default')
            ->type(0, 'tht_charge_per')
            ->type(3, 'bga_default')
            ->type('1.50', 'bga_charge_per')
            ->type(2, 'sides_default')
            ->type(75, 'sides_charge_per')
            ->type(1, 'flat_fee_default')
            ->type(200, 'flat_fee_charge_per')
            ->type('1.25', 'function_x')
            ->type('0.05', 'function_b')
            ->type(2, '2_layer_sq_inch_price')
            ->type(600, '2_layer_base_price')
            ->type(3, '4_layer_sq_inch_price')
            ->type(700, '4_layer_base_price')
            ->type(4, '6_layer_sq_inch_price')
            ->type(900, '6_layer_base_price')
            ->type(5, '8_layer_sq_inch_price')
            ->type(1000, '8_layer_base_price')
            ->type(100, 'assembled_turn_day_1')
            ->type(90, 'assembled_turn_day_2')
            ->type(85, 'assembled_turn_day_3')
            ->type(80, 'assembled_turn_day_4')
            ->type(75, 'assembled_turn_day_5')
            ->type(100, 'unassembled_turn_day_1')
            ->type(90, 'unassembled_turn_day_2')
            ->type(85, 'unassembled_turn_day_3')
            ->type(80, 'unassembled_turn_day_4')
            ->type(75, 'unassembled_turn_day_5')
            ->press('submitQuoteForm')
            ->seePageIs('/admin/settings/1/edit')
            ->see('Edit active quote function')
            ->see('Could not create new settings')
            ->see('The bom line default field is required.')
            ->see('The bom line charge per must be a number.')
            ->see('The quantity default must be an integer.')
            ->dontSeeInDatabase('admin_settings', ['bom_line_charge_per' => '1.75'])
            ->seeInDatabase('admin_settings', ['active' => '1'])
            ->dontSeeInDatabase('admin_settings', ['active' => '0']);
    }

    public function testEditInactiveSettings()
    {
        // Reset to a factory db
        $this->resetDB();
        $this->adminLogin();
        factory(App\Admin\AdminSettings::class)->create();
        $this->visit('admin/settings/1/edit')
            ->see('Edit active quote function')
            ->see('Back to index')
            ->type('10', 'quantity_default')
            ->type(100, 'bom_line_default')
            ->type('1.75', 'bom_line_charge_per')
            ->type(150, 'smt_default')
            ->type('.25', 'smt_charge_per')
            ->type(10, 'tht_default')
            ->type(0, 'tht_charge_per')
            ->type(3, 'bga_default')
            ->type('1.50', 'bga_charge_per')
            ->type(2, 'sides_default')
            ->type(75, 'sides_charge_per')
            ->type(1, 'flat_fee_default')
            ->type(200, 'flat_fee_charge_per')
            ->type('1.25', 'function_x')
            ->type('0.05', 'function_b')
            ->type(2, '2_layer_sq_inch_price')
            ->type(600, '2_layer_base_price')
            ->type(3, '4_layer_sq_inch_price')
            ->type(700, '4_layer_base_price')
            ->type(4, '6_layer_sq_inch_price')
            ->type(900, '6_layer_base_price')
            ->type(5, '8_layer_sq_inch_price')
            ->type(1000, '8_layer_base_price')
            ->type(100, 'assembled_turn_day_1')
            ->type(90, 'assembled_turn_day_2')
            ->type(85, 'assembled_turn_day_3')
            ->type(80, 'assembled_turn_day_4')
            ->type(75, 'assembled_turn_day_5')
            ->type(100, 'unassembled_turn_day_1')
            ->type(90, 'unassembled_turn_day_2')
            ->type(85, 'unassembled_turn_day_3')
            ->type(80, 'unassembled_turn_day_4')
            ->type(75, 'unassembled_turn_day_5')
            ->press('submitQuoteForm')
            ->seePageIs('/admin/settings/3/edit')
            ->see('Edit active quote function')
            ->seeInDatabase('admin_settings', ['bom_line_charge_per' => '1.75'])
            ->seeInDatabase('admin_settings', ['active' => '0'])
            ->seeInDatabase('admin_settings', ['active' => '1'])
            ->visit('/admin/settings/1/edit')
            ->seePageIs('/admin/settings')
            ->see('Cannot edit inactive settings');
    }
}
