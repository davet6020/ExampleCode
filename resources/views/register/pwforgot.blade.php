@extends('layouts.admin')
@include('layouts.menu')

<h3>Type in your email address and press Send.  Instructions will be sent to your email</h3>
{!! Form::open(array('action' => 'RegisterController@pwForgot')) !!}
    Email: {!! Form::text('email') !!} <br>
    {!! Form::submit('Send') !!}
{!! Form::close() !!}

