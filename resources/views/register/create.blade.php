@extends('layouts.admin')
@include('layouts.menu')

@if(isset($message))
  <h2>{{ $message }}</h2>
@endif

{!! Form::open(array('url' => 'register')) !!}
    First Name: {!! Form::text('first_name') !!} <br>
    Last Name: {!! Form::text('last_name') !!} <br>
    Email: {!! Form::text('email') !!} <br>
    Password: {!! Form::password('password') !!} <br>
    Company: {!! Form::text('company') !!} <br>
    {!! Form::submit('Create My Account') !!}
{!! Form::close() !!}