@extends('layouts.admin')
@include('layouts.menu')

{!! Form::model($user, array('route' => array('register.update', $user->id), 'method' => 'put')) !!}
    First Name: {!! Form::text('first_name') !!} <br>
    Last Name: {!! Form::text('last_name') !!} <br>
    Email: {!! Form::text('email') !!} <br>
    Password: {!! Form::password('password') !!} <br>
    Company: {!! Form::text('company') !!} <br>
    Cell Phone: {!! Form::text('cell_phone') !!} <br>
    Home Phone: {!! Form::text('home_phone') !!} <br>
    Work Phone: {!! Form::text('work_phone') !!} <br>

  <br>
    <div class="form-group">
      <label for="billing">Billing Address</label><br>
        @if(isset($billing))
            {{ Form::hidden('billing_type', 'Billing') }}
            Full_name: {!! Form::text('billing_full_name', $billing['full_name']) !!} <br>
            Name_on_cc: {!! Form::text('billing_name_on_cc', $billing['name_on_cc']) !!} <br>
            Address_1: {!! Form::text('billing_address_1', $billing['address_1']) !!} <br>
            Address_2: {!! Form::text('billing_address_2', $billing['address_2']) !!} <br>
            City: {!! Form::text('billing_city', $billing['city']) !!} <br>
            State: {!! Form::text('billing_state', $billing['state']) !!} <br>
            Zip: {!! Form::text('billing_zip', $billing['zip']) !!} <br>
            Country: {!! Form::text('billing_country', $billing['country']) !!} <br>
        @else
            {{ Form::hidden('billing_type', 'Billing') }}
            Full_name: {!! Form::text('billing_full_name', null) !!} <br>
            Name_on_cc: {!! Form::text('billing_name_on_cc', null) !!} <br>
            Address_1: {!! Form::text('billing_address_1', null) !!} <br>
            Address_2: {!! Form::text('billing_address_2', null) !!} <br>
            City: {!! Form::text('billing_city', null) !!} <br>
            State: {!! Form::text('billing_state', null) !!} <br>
            Zip: {!! Form::text('billing_zip', null) !!} <br>
            Country: {!! Form::text('billing_country', null) !!} <br>
        @endif
    </div>

  <br>
    <div class="form-group">
      <label for="company">Company Address</label><br>
        @if(isset($company))
            {{ Form::hidden('company_type', 'Company') }}
            Full_name: {!! Form::text('company_full_name', $company['full_name']) !!} <br>
            Name_on_cc: {!! Form::text('company_name_on_cc', $company['name_on_cc']) !!} <br>
            Address_1: {!! Form::text('company_address_1', $company['address_1']) !!} <br>
            Address_2: {!! Form::text('company_address_2', $company['address_2']) !!} <br>
            City: {!! Form::text('company_city', $company['city']) !!} <br>
            State: {!! Form::text('company_state', $company['state']) !!} <br>
            Zip: {!! Form::text('company_zip', $company['zip']) !!} <br>
            Country: {!! Form::text('company_country', $company['country']) !!} <br>
        @else
            {{ Form::hidden('company_type', 'Company') }}
            Full_name: {!! Form::text('company_full_name', null) !!} <br>
            Name_on_cc: {!! Form::text('company_name_on_cc', null) !!} <br>
            Address_1: {!! Form::text('company_address_1', null) !!} <br>
            Address_2: {!! Form::text('company_address_2', null) !!} <br>
            City: {!! Form::text('company_city', null) !!} <br>
            State: {!! Form::text('company_state', null) !!} <br>
            Zip: {!! Form::text('company_zip', null) !!} <br>
            Country: {!! Form::text('company_country', null) !!} <br>
        @endif
    </div>

  <br>
    <div class="form-group">
      <label for="shipping">Shipping Address</label><br>
        @if(isset($shipping))
            {{ Form::hidden('shipping_type', 'Shipping') }}
            Full_name: {!! Form::text('shipping_full_name', $shipping['full_name']) !!} <br>
            Name_on_cc: {!! Form::text('shipping_name_on_cc', $shipping['name_on_cc']) !!} <br>
            Address_1: {!! Form::text('shipping_address_1', $shipping['address_1']) !!} <br>
            Address_2: {!! Form::text('shipping_address_2', $shipping['address_2']) !!} <br>
            City: {!! Form::text('shipping_city', $shipping['city']) !!} <br>
            State: {!! Form::text('shipping_state', $shipping['state']) !!} <br>
            Zip: {!! Form::text('shipping_zip', $shipping['zip']) !!} <br>
            Country: {!! Form::text('shipping_country', $shipping['country']) !!} <br>
        @else
            {{ Form::hidden('shipping_type', 'Shipping') }}
            Full_name: {!! Form::text('shipping_full_name', null) !!} <br>
            Name_on_cc: {!! Form::text('shipping_name_on_cc', null) !!} <br>
            Address_1: {!! Form::text('shipping_address_1', null) !!} <br>
            Address_2: {!! Form::text('shipping_address_2', null) !!} <br>
            City: {!! Form::text('shipping_city', null) !!} <br>
            State: {!! Form::text('shipping_state', null) !!} <br>
            Zip: {!! Form::text('shipping_zip', null) !!} <br>
            Country: {!! Form::text('shipping_country', null) !!} <br>
        @endif
    </div>

    {!! Form::submit('Update') !!}
{!! Form::close() !!}
