@extends('layouts.admin')
@include('layouts.menu')

@if(isset($message))
  <h2>{{ $message }}</h2>
@endif

{!! Form::open(array('url' => 'user')) !!}
    First Name: {!! Form::text('first_name') !!} <br>
    Last Name: {!! Form::text('last_name') !!} <br>
    Email: {!! Form::text('email') !!} <br>
    Password: {!! Form::password('password') !!} <br>
    Company: {!! Form::text('company') !!} <br>
    Has Terms: {!! Form::select('has_terms', array('0' => 'No', '1' => 'Yes'), '0') !!} <br/>
    Account Number: {!! Form::text('account_number') !!} <br>
    {!! Form::submit('Make a user!') !!}
{!! Form::close() !!}
