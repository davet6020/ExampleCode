@extends('layouts.admin')
@include('layouts.menu')

{!! Form::open(array('action' => 'UsersController@loginTest')) !!}
    Email: {!! Form::text('email') !!} <br>
    Password: {!! Form::password('password') !!} <br>
    {!! Form::submit('Login') !!}
{!! Form::close() !!}