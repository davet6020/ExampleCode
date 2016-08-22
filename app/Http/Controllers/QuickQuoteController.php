<?php

namespace App\Http\Controllers;

use App\Admin\AdminSettings;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class QuickQuoteController
 * @package App\Http\Controllers
 */
class QuickQuoteController extends Controller
{

    public function apiDefaults()
    {
        $defaults = AdminSettings::where('active', '=', 1)->first(['bom_line_default','smt_default','tht_default','bga_default','sides_default','flat_fee_default','quantity_default']);

        if (empty($defaults)) {
            $this->abort('No Defaults Found');
        }

        $this->viewVars['defaults'] = $defaults->toArray();
        return $this->json();
    }

    public function apiCalculate()
    {
        $input = $this->request->input();
        $mainValidator = \Validator::make($input, [
            'bgas'                  => 'required|integer|min:0',
            'bom_lines'             => 'required|integer|min:0',
            'quantity'              => 'required|integer|min:0',
            'smt'                   => 'required|integer|min:0',
        ]);
        if ($mainValidator->fails()) {
            $this->viewVars['errors'] = $this->formatValidationErrors($mainValidator->errors());
            return $this->json(400);
        }
        if (isset($input['unassembled_quantity']) && $input['unassembled_quantity'] > 0) {
            $uValidator = \Validator::make($input, [
                'width'                 => 'required|min:0|between:0,99.99|numeric',
                'height'                => 'required|min:0|between:0,99.99|numeric',
                'finish'                => 'required',
                'layers'                => 'required',
                'unassembled_turn_time' => 'required'
            ]);
            if ($uValidator->fails()) {
                $this->viewVars['errors'] = $this->formatValidationErrors($uValidator->errors());
                return $this->json(400);
            }
        }
        $defaults = AdminSettings::where('active', '=', 1)->firstOrFail();

        if (empty($defaults)) {
            $this->abort('No calculation active.');
        }

        // Log the quote
        $quoteToLog = new \App\QuickQuoteLog();
        $quoteToLog->fill($input);
        if (!empty($this->user)) {
            $quoteToLog->user_id = $this->user->id;
        }
        $quoteToLog->formula_id = $defaults->id;
        $quoteToLog->ip = request()->ip();

        $quoteToLog->save();

        $this->viewVars = array_merge($this->viewVars, $this->process($defaults, $input));
        return $this->json();
    }

    /**
     * @return $this
     */
    public function index()
    {
        try {
            $defaults = AdminSettings::where('active', '=', 1)->firstOrFail();
            return $this->render('quickQuote.index')->with('defaults', $defaults);
        } catch (ModelNotFoundException $e) {
            $this->request->session()->flash('error', 'No active admin settings found');
            return redirect('/');
        }
    }

    /**
     * Collect array and begin processing
     * @return array
     */
    public function input()
    {
        $input = $this->request->input();
        $mainValidator = \Validator::make($input, [
            'assembled_turn_time'   => 'required',
            'bgas'                  => 'required|integer|min:0',
            'bom_lines'             => 'required|integer|min:0',
            'quantity'              => 'required|integer|min:0',
            'smt'                   => 'required|integer|min:0',
        ]);
        if ($mainValidator->fails()) {
            return json_encode([
                'success'   => false,
                'error'     => 'Assembly input missing',
                'errors'    => $mainValidator->errors()
            ]);
        }
        if (isset($input['unassembled_quantity']) && $input['unassembled_quantity'] > 0) {
            $uValidator = \Validator::make($input, [
                'width'                 => 'required|min:0|between:0,99.99|numeric',
                'height'                => 'required|min:0|between:0,99.99|numeric',
                'finish'                => 'required',
                'layers'                => 'required',
                'unassembled_turn_time' => 'required'
            ]);
            if ($uValidator->fails()) {
                return json_encode([
                    'success'   => false,
                    'error'     => 'Boards input missing',
                    'errors'    => $uValidator->errors()
                ]);
            }
        }
        try {
            $defaults = AdminSettings::where('active', '=', 1)->firstOrFail();
            return  $this->process($defaults, $input);
        } catch (ModelNotFoundException $e) {
            $this->request->session()->flash('error', 'No active admin settings found');
            return redirect('/');
        }
    }

    /**
     * Process form
     *
     * @param $defaults
     * @param $input
     * @return array
     */
    protected function process($defaults, $input)
    {
        $data = [];
        $data['bom_price'] = $input['bom_lines'] * $defaults['bom_line_charge_per'];
        $data['smt_price'] = $input['smt'] * $defaults['smt_charge_per'];
        //$data['tht_price'] = number_format($input['tht'] * $defaults['tht_charge_per'], 2);
        $data['bgas_price'] = $input['bgas'] * $defaults['bga_charge_per'];
        $sides = 1;
        if (isset($input['sides']) && $input['sides'] == 'true') {
            $sides = 2;
        }
        $data['sides_price'] = $sides * $defaults['sides_charge_per'];
        $data['flat_fee_price'] = $defaults['flat_fee_charge_per'];

        $basePrice = 0;
        foreach ($data as $d) {
            $basePrice = $basePrice + $d;
        }

        $retval = [];

        if (isset($input['unassembled_quantity']) && $input['unassembled_quantity'] !== '') {
            $layer = $input['layers'];
            $uBasePrice = $defaults[$layer . 'base_price'];
            $uSqInPrice = $defaults[$layer . 'sq_inch_price'];
            $unassembledBoardPrice = (($input['width'] * $input['height']) * $uSqInPrice) + $uBasePrice;
            $retval['boards'] = [
                'subTotal' => $unassembledBoardPrice,
                'total' => ($unassembledBoardPrice * $input['unassembled_quantity']) * ((float)$input['unassembled_turn_time'] + 1),
            ];
        }

        $turnTime = (!empty($input['assembled_turn_time'])) ? (float)$input['assembled_turn_time'] : 1;
        $retval['assembly'] =  $this->calculateMultiplier($input['quantity'], $basePrice, $turnTime, $defaults);
        $retval['total'] = $retval['assembly']['total'];
        if (isset($retval['boards'])) {
            $retval['total'] = $retval['total'] + $retval['boards']['total'];
        }
        return $retval;
    }

    /**
     * Calculate the multipliers for boards
     *
     * @param $quantity
     * @param $price
     * @param $aTurn
     * @param $defaults
     * @return array
     */
    protected function calculateMultiplier($quantity, $price, $aTurn, $defaults)
    {
        $multiplier = 1;
        $multipliers = [];
        $prices = [];
        $counter = 0;
        while ($counter < $quantity) {
            $prices[] = round($price * $multiplier, 2);
            $multipliers[] = $multiplier;
            $multiplier = round(($multiplier / $defaults['function_x']) + $defaults['function_b'], 10);
            $counter = $counter + 1;
        }

        $average = round(array_sum($multipliers) / count($multipliers), 10);
        $unitPrice = $price * $average;

        // Apply the turn time here
        $lotPrice = $unitPrice * $quantity;
        $finalLotPrice = $lotPrice * ($aTurn);

        return array(
            'price' => $price,
            'lotPrice' => $lotPrice,
            'average' => $average * 100,
            'unitPrice' => $unitPrice,
            'total' => $finalLotPrice,
            // 'multipliers' => $multipliers
        );
    }
}
