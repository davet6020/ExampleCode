@extends('layouts.admin')
@include('layouts.menu')

{!! Form::open(array('action' => 'RegisterController@pwSetNew')) !!}
    Password: {!! Form::password('password') !!} <br>
    Confirm Password: {!! Form::password('password2') !!} <br>
    {!! Form::submit('Reset Password') !!}

    {{ Form::hidden('id', $id) }}
{!! Form::close() !!}
