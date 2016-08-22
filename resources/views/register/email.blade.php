@extends('layouts.admin')
@include('layouts.menu')

{!! Form::open(array('action' => 'RegisterController@emailTest')) !!}
    User ID: {!! Form::text('first_name') !!} <br>
    Email: {!! Form::text('email') !!} <br>
    {!! Form::submit('Create My Account') !!}
{!! Form::close() !!}