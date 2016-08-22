@extends('layouts.admin')

@section('content')
    <h3>Edit active quote function</h3>
    <div class="form-group">
        <a href="{{ URL::to('/admin/settings') }}">Back to index</a>
    </div>
    @if (Session::has('error'))
        <p class="text-danger">{{ Session::get('error') }}</p>
    @endif
    {!! Form::open(array('url' => 'admin/settings/' . $data['id'], 'method' => 'PUT')) !!}
    <div class="row">
        <div class="col-md-6">
            <h3>Assembled Board</h3>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('bom_line_default')) ? 'has-error' : '' }}">
                    {!! Form::label('bom_line_default', 'BOM Line Default') !!}
                    {!! Form::text('bom_line_default', $data['bom_line_default'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('bom_line_default') ? $errors->first('bom_line_default', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('bom_line_charge_per')) ? 'has-error' : '' }}">
                    {!! Form::label('bom_line_charge_per', 'BOM Line Charge Per') !!}
                    {!! Form::text('bom_line_charge_per', $data['bom_line_charge_per'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('bom_line_charge_per') ? $errors->first('bom_line_charge_per', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('smt_default')) ? 'has-error' : '' }}">
                    {!! Form::label('smt_default', 'SMT Default') !!}
                    {!! Form::text('smt_default', $data['smt_default'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('smt_default') ? $errors->first('smt_default', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('smt_charge_per')) ? 'has-error' : '' }}">
                    {!! Form::label('smt_charge_per', 'SMT Charge Per') !!}
                    {!! Form::text('smt_charge_per', $data['smt_charge_per'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('smt_charge_per') ? $errors->first('smt_charge_per', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('tht_default')) ? 'has-error' : '' }}">
                    {!! Form::label('tht_default', 'THT Default') !!}
                    {!! Form::text('tht_default', $data['tht_default'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('tht_default') ? $errors->first('tht_default', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('tht_charge_per')) ? 'has-error' : '' }}">
                    {!! Form::label('tht_charge_per', 'THT Charge Per') !!}
                    {!! Form::text('tht_charge_per', $data['tht_charge_per'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('tht_charge_per') ? $errors->first('tht_charge_per', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('bga_default')) ? 'has-error' : '' }}">
                    {!! Form::label('bga_default', 'BGA Default') !!}
                    {!! Form::text('bga_default', $data['bga_default'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('bga_default') ? $errors->first('bga_default', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('bga_charge_per')) ? 'has-error' : '' }}">
                    {!! Form::label('bga_charge_per', 'BGA Charge Per') !!}
                    {!! Form::text('bga_charge_per', $data['bga_charge_per'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('bga_charge_per') ? $errors->first('bga_charge_per', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('sides_default')) ? 'has-error' : '' }}">
                    {!! Form::label('sides_default', 'Sides Default') !!}
                    {!! Form::text('sides_default', $data['sides_default'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('sides_default') ? $errors->first('sides_default', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('sides_charge_per')) ? 'has-error' : '' }}">
                    {!! Form::label('sides_charge_per', 'Sides Charge Per') !!}
                    {!! Form::text('sides_charge_per', $data['sides_charge_per'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('sides_charge_per') ? $errors->first('sides_charge_per', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('flat_fee_default')) ? 'has-error' : '' }}">
                    {!! Form::label('flat_fee_default', 'Flat Fee Default') !!}
                    {!! Form::text('flat_fee_default', $data['flat_fee_default'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('flat_fee_default') ? $errors->first('flat_fee_default', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('flat_fee_charge_per')) ? 'has-error' : '' }}">
                    {!! Form::label('flat_fee_charge_per', 'Flat Fee Charge Per') !!}
                    {!! Form::text('flat_fee_charge_per', $data['flat_fee_charge_per'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('flat_fee_charge_per') ? $errors->first('flat_fee_charge_per', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 {{ ($errors->has('function_x')) ? 'has-error' : '' }}">
                    {!! Form::label('function_x', 'Function X') !!}
                    {!! Form::text('function_x', $data['function_x'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('function_x') ? $errors->first('function_x', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-4 {{ ($errors->has('function_b')) ? 'has-error' : '' }}">
                    {!! Form::label('function_b', 'Function B') !!}
                    {!! Form::text('function_b', $data['function_b'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('function_b') ? $errors->first('function_b', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-4 {{ ($errors->has('quantity_default')) ? 'has-error' : '' }}">
                    {!! Form::label('quantity_default', 'Quantity Default') !!}
                    {!! Form::text('quantity_default', $data['quantity_default'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('quantity_default') ? $errors->first('quantity_default', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 {{ ($errors->has('assembled_turn_day_1')) ? 'has-error' : '' }}">
                    {!! Form::label('assembled_turn_day_1', 'Turn Day 1 *') !!}
                    {!! Form::text('assembled_turn_day_1', $data['assembled_turn_day_1'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('assembled_turn_day_1') ? $errors->first('assembled_turn_day_1', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-4 {{ ($errors->has('assembled_turn_day_2')) ? 'has-error' : '' }}">
                    {!! Form::label('assembled_turn_day_2', 'Turn Day 2 *') !!}
                    {!! Form::text('assembled_turn_day_2', $data['assembled_turn_day_2'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('assembled_turn_day_2') ? $errors->first('assembled_turn_day_2', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-4 {{ ($errors->has('assembled_turn_day_3')) ? 'has-error' : '' }}">
                    {!! Form::label('assembled_turn_day_3', 'Turn Day 3 *') !!}
                    {!! Form::text('assembled_turn_day_3', $data['assembled_turn_day_3'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('assembled_turn_day_3') ? $errors->first('assembled_turn_day_3', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 {{ ($errors->has('assembled_turn_day_4')) ? 'has-error' : '' }}">
                    {!! Form::label('assembled_turn_day_4', 'Turn Day 4 *') !!}
                    {!! Form::text('assembled_turn_day_4', $data['assembled_turn_day_4'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('assembled_turn_day_4') ? $errors->first('assembled_turn_day_4', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-4 {{ ($errors->has('assembled_turn_day_5')) ? 'has-error' : '' }}">
                    {!! Form::label('assembled_turn_day_5', 'Turn Day 5 *') !!}
                    {!! Form::text('assembled_turn_day_5', $data['assembled_turn_day_5'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('assembled_turn_day_5') ? $errors->first('assembled_turn_day_5', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h3>Unassembled Board</h3>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('2_layer_base_price')) ? 'has-error' : '' }}">
                    {!! Form::label('2_layer_base_price', '2 Layer Base Price') !!}
                    {!! Form::text('2_layer_base_price', $data['2_layer_base_price'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('2_layer_base_price') ? $errors->first('2_layer_base_price', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('2_layer_sq_inch_price')) ? 'has-error' : '' }}">
                    {!! Form::label('2_layer_sq_inch_price', '2 Layer Sq. Inch Price') !!}
                    {!! Form::text('2_layer_sq_inch_price', $data['2_layer_sq_inch_price'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('2_layer_sq_inch_price') ? $errors->first('2_layer_sq_inch_price', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('4_layer_base_price')) ? 'has-error' : '' }}">
                    {!! Form::label('4_layer_base_price', '4 Layer Base Price') !!}
                    {!! Form::text('4_layer_base_price', $data['4_layer_base_price'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('4_layer_base_price') ? $errors->first('4_layer_base_price', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('4_layer_sq_inch_price')) ? 'has-error' : '' }}">
                    {!! Form::label('4_layer_sq_inch_price', '4 Layer Sq. Inch Price') !!}
                    {!! Form::text('4_layer_sq_inch_price', $data['4_layer_sq_inch_price'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('4_layer_sq_inch_price') ? $errors->first('4_layer_sq_inch_price', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('6_layer_base_price')) ? 'has-error' : '' }}">
                    {!! Form::label('6_layer_base_price', '6 Layer Base Price') !!}
                    {!! Form::text('6_layer_base_price', $data['6_layer_base_price'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('6_layer_base_price') ? $errors->first('6_layer_base_price', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('6_layer_sq_inch_price')) ? 'has-error' : '' }}">
                    {!! Form::label('6_layer_sq_inch_price', '6 Layer Sq. Inch Price') !!}
                    {!! Form::text('6_layer_sq_inch_price', $data['6_layer_sq_inch_price'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('6_layer_sq_inch_price') ? $errors->first('6_layer_sq_inch_price', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 {{ ($errors->has('8_layer_base_price')) ? 'has-error' : '' }}">
                    {!! Form::label('8_layer_base_price', '8 Layer Base Price') !!}
                    {!! Form::text('8_layer_base_price', $data['8_layer_base_price'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('8_layer_base_price') ? $errors->first('8_layer_base_price', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-6 {{ ($errors->has('8_layer_sq_inch_price')) ? 'has-error' : '' }}">
                    {!! Form::label('8_layer_sq_inch_price', '8 Layer Sq. Inch Price') !!}
                    {!! Form::text('8_layer_sq_inch_price', $data['8_layer_sq_inch_price'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('8_layer_sq_inch_price') ? $errors->first('8_layer_sq_inch_price', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 {{ ($errors->has('unassembled_turn_day_1')) ? 'has-error' : '' }}">
                    {!! Form::label('unassembled_turn_day_1', 'Turn Day 1 *') !!}
                    {!! Form::text('unassembled_turn_day_1', $data['unassembled_turn_day_1'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('unassembled_turn_day_1') ? $errors->first('unassembled_turn_day_1', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-4 {{ ($errors->has('unassembled_turn_day_2')) ? 'has-error' : '' }}">
                    {!! Form::label('unassembled_turn_day_2', 'Turn Day 2 *') !!}
                    {!! Form::text('unassembled_turn_day_2', $data['unassembled_turn_day_2'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('unassembled_turn_day_2') ? $errors->first('unassembled_turn_day_2', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-4 {{ ($errors->has('unassembled_turn_day_3')) ? 'has-error' : '' }}">
                    {!! Form::label('unassembled_turn_day_3', 'Turn Day 3 *') !!}
                    {!! Form::text('unassembled_turn_day_3', $data['unassembled_turn_day_3'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('unassembled_turn_day_3') ? $errors->first('unassembled_turn_day_3', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 {{ ($errors->has('unassembled_turn_day_4')) ? 'has-error' : '' }}">
                    {!! Form::label('unassembled_turn_day_4', 'Turn Day 4 *') !!}
                    {!! Form::text('unassembled_turn_day_4', $data['unassembled_turn_day_4'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('unassembled_turn_day_4') ? $errors->first('unassembled_turn_day_4', '<p class="text-danger">:message</p>') : '') !!}
                </div>
                <div class="form-group col-md-4 {{ ($errors->has('unassembled_turn_day_5')) ? 'has-error' : '' }}">
                    {!! Form::label('unassembled_turn_day_5', 'Turn Day 5 *') !!}
                    {!! Form::text('unassembled_turn_day_5', $data['unassembled_turn_day_5'], ['class' => 'form-control']) !!}
                    {!! ($errors->has('unassembled_turn_day_5') ? $errors->first('unassembled_turn_day_5', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
    </div>
    {{ Form::hidden('active', 1) }}
    {{ Form::submit('Submit', ['class' => 'btn-lg btn-primary', 'id' => 'submitQuoteForm']) }}
    {!! Form::close() !!}
@stop