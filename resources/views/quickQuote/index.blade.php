@extends('layouts/admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/quote.css') }}">
@stop

@section('content')
    @if (Session::has('error'))
        <p class="text-danger">{{ Session::get('error') }}</p>
    @endif
    {!! Form::open(array('url' => 'quote/quick', 'method' => 'POST', 'id' => 'quoteForm')) !!}
    <div class="col-md-8">
        <h3>ASSEMBLY CALCULATOR</h3>
        <p>Text stuff here</p>
        <h4>Product</h4>
        <hr />
        <div class="form-group {{ ($errors->has('quantity')) ? 'has-errors' : '' }}">
            {{ Form::label('quantity', 'Quantity') }}
            {{ Form::text('quantity', $defaults['quantity_default'], array('id' => 'quantity', 'class' => 'form-control required number')) }}
            {!! ($errors->has('quantity') ? $errors->first('quantity', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('bom_lines')) ? 'has-errors' : '' }}">
            {{ Form::label('bom_lines', 'Unique Part Count (BOM Lines)') }}
            {{ Form::text('bom_lines', $defaults['bom_line_default'], array('id' => 'bom_lines', 'class' => 'form-control required number')) }}
            {!! ($errors->has('bom_lines') ? $errors->first('bom_lines', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('smt')) ? 'has-errors' : '' }}">
            {{ Form::label('smt', 'Total Parts Count') }}
            {{ Form::text('smt', $defaults['smt_default'], array('id' => 'smt', 'class' => 'form-control required number')) }}
            {!! ($errors->has('smt') ? $errors->first('smt', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('bgas')) ? 'has-errors' : '' }}">
            {{ Form::label('bgas', 'Number of BGAs') }}
            {{ Form::text('bgas', $defaults['bga_default'], array('id' => 'bgas', 'class' => 'form-control required number')) }}
            {!! ($errors->has('bgas') ? $errors->first('bgas', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group">
            <label for="assembled_turn_time">Turn Time</label>
            <select id="assembled_turn_time" name="assembled_turn_time" class="form-control">
                <option value="{{ $defaults['assembled_turn_day_1'] / 100 }}">1 Day Turn</option>
                <option value="{{ $defaults['assembled_turn_day_2'] / 100 }}">2 Day Turn</option>
                <option value="{{ $defaults['assembled_turn_day_3'] / 100 }}">3 Day Turn</option>
                <option value="{{ $defaults['assembled_turn_day_4'] / 100 }}">4 Day Turn</option>
                <option value="{{ $defaults['assembled_turn_day_5'] / 100 }}">5 Day Turn</option>
                <option value="0" selected>> 5 Day Turn</option>
            </select>
        </div>
        <div class="form-group">
            <div class="col-md-3">
                {{ Form::checkbox('sides', 2, true) }}
                {{ Form::label('sides', 'Double Side') }}
            </div>
            <div class="col-md-3">
                {{ Form::radio('itar', 1) }}
                {{ Form::label('itar', 'ITAR Compliant') }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::submit('Add Your Boards', array('class' => 'btn btn-block btn-primary btn-lg', 'id' => 'submitQuoteForm,')) }}
        </div>
        <h3>BOARDS CALCULATOR</h3>
        <p>Text here</p>
        <h4>PRODUCT</h4>
        <hr />
        <div class="form-group">
            {{ Form::label('unassembled_quantity', 'Extra Boards') }}
            {{ Form::text('unassembled_quantity', old('unassembled_quantity'), ['class' => 'form-control number', 'placeholder' => '5', 'id' => 'unassembled_quantity']) }}
        </div>
        <div class="form-group">
            {{ Form::label('board_dimensions', 'Board Dimensions') }}
            <div class="row">
                <div class="col-md-6">
                    {{ Form::text('width', null, ['class' => 'form-control number', 'placeholder' => 'Width (in)', 'id' => 'width']) }}
                </div>
                <div class="col-md-6">
                    {{ Form::text('height', null, ['class' => 'form-control number', 'placeholder' => 'Height (in)', 'id' => 'height']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('layers', 'Layers') }}
            {{ Form::select('layers', [
            '2_layer_'=>2,
            '4_layer_'=>4,
            '6_layer_'=>6,
            '8_layer_'=>8
            ], null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            {{ Form::label('finish', 'Finish') }}
            {{ Form::select('finish', ['finish1' => 'Finish 1', 'finish2' => 'Finish 2'], null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            <label for="unassembled_turn_time">Turn Time</label>
            <select id="unassembled_turn_time" name="unassembled_turn_time" class="form-control">
                <option value="{{ $defaults['unassembled_turn_day_1'] / 100 }}">1 Day Turn</option>
                <option value="{{ $defaults['unassembled_turn_day_2'] / 100 }}">2 Day Turn</option>
                <option value="{{ $defaults['unassembled_turn_day_3'] / 100 }}">3 Day Turn</option>
                <option value="{{ $defaults['unassembled_turn_day_4'] / 100 }}">4 Day Turn</option>
                <option value="{{ $defaults['unassembled_turn_day_5'] / 100 }}" selected>5 Day Turn</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <h3>NEXT STEPS</h3>
        <p><a href="#" class="btn btn-primary btn-block btn-lg">Start a Project</a></p>
        <p><a href="#" class="btn btn-primary btn-block btn-lg">BOM Inspector</a></p>
        <p><a href="#" class="btn btn-primary btn-block btn-lg">DFA Checker</a></p>
        <hr />
        <div class="form-group" id="quoteDiv">
            <span id="quoteHead">QUOTE</span>
            <div id="quoteWrap">
                <div class="row quoteRow">
                    <div class="col-md-6">
                        <span class="quoteTitle">Assembly</span>
                    </div>
                    <div class="col-md-6 amountDiv">
                        <span class="quoteAmount" id="assemblySub">$0</span>
                    </div>
                </div>
                <div class="row quoteRow">
                    <div class="col-md-6">
                        <span class="quoteTitle">Boards</span>
                    </div>
                    <div class="col-md-6 amountDiv">
                        <span class="quoteAmount" id="boardSub">$0</span>
                    </div>
                </div>
                <hr class="quoteHr"/>
                <div class="row quoteRow">
                    <div class="col-md-7">
                        <span id="quoteTotal">$ 0</span>
                    </div>
                    <div class="col-md-5">
                        <span id="quoteUnit">$0</span> <br />per board
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var quoteForm = $('form#quoteForm');
            quoteForm.validate({
                rules : {
                    width : {
                        required : function () {
                            return $('input#unassembled_quantity').val().length > 0;
                        }
                    },
                    height : {
                        required : function () {
                            return $('input#unassembled_quantity').val().length > 0;
                        }
                    }
                },
                messages : {
                    width : {
                        required: 'This field is required when extra boards are requested.'
                    },
                    height : {
                        required: 'This field is required when extra boards are requested.'
                    }
                }
            });
            quoteForm.submit(function (e) {
                e.preventDefault();
                if (quoteForm.valid()) {
                    $.ajax({
                        url: '/quote/quick',
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (data) {
                            if (data.success) {
                                if (data.assembled) {
                                    $('span#assemblySub').text('$' + data.assembled.finalPrice);
                                }
                                if (data.unAssembled) {
                                    $('span#boardSub').text('$' + data.unAssembled.finalPrice);
                                }
                                $('span#quoteTotal').text('$ ' + data.total);
                                $('span#quoteUnit').text('$' + data.assembled.unitPrice);
                            }
                        },
                        error: function (data) {
                            console.log('Could not submit form');
                            console.log(data);
                        }
                    });
                }
            });
        });
    </script>
@stop