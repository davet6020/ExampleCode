<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AdminController extends Controller
{

    public function quickQuoteExport()
    {
        $logs = \App\QuickQuoteLog::with('user')->with('formula')->get();

        $formulaValues = ['bom_line_charge_per', 'smt_charge_per', 'tht_charge_per', 'bga_charge_per', 'sides_charge_per',
        'flat_fee_charge_per', 'turn_day_discounts', 'function_x', 'function_b',
        'assembled_turn_day_1', 'assembled_turn_day_2', 'assembled_turn_day_3', 'assembled_turn_day_4',
        'assembled_turn_day_5', '2_layer_base_price', '2_layer_sq_inch_price', '4_layer_base_price',
        '4_layer_sq_inch_price', '6_layer_base_price', '6_layer_sq_inch_price', '8_layer_base_price',
        '8_layer_sq_inch_price', 'unassembled_turn_day_1', 'unassembled_turn_day_2', 'unassembled_turn_day_3',
        'unassembled_turn_day_4', 'unassembled_turn_day_5'];

        $csvRows = [['date', 'bgas', 'bom_lines', 'quantity', 'sides', 'smt', 'formula_id', 'ip', 'user_email'] + $formulaValues];



        foreach ($logs as $log) {
            $csvRowToAdd = array_values(array_only($log->toArray(), ['bgas', 'bom_lines', 'quantity', 'sides', 'smt', 'formula_id', 'ip']));
            $csvRowToAdd[] = ($log->user) ? $log->user->email : '';
            array_unshift($csvRowToAdd, (string) $log->created_at);

            $formula = array_values(array_only($log->formula->toArray(), $formulaValues));

            $csvRows[] = $csvRowToAdd + $formula;
        }

        \Excel::create('QuickQuoteLog', function ($excel) use ($csvRows) {
            $excel->sheet('Sheet 1', function ($sheet) use ($csvRows) {
                $sheet->fromArray($csvRows);
            });
        })->export('csv');
    }

    // Pre Angular Stuff

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return $this->render('admin/index');
    }
}
