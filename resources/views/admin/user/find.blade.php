@extends('layouts.admin')
@include('layouts.menu')

{!! Form::open(array('action' => 'UsersController@findByName')) !!}
    Email: {!! Form::text('email') !!} <br>
    {!! Form::submit('Find a user!') !!}
{!! Form::close() !!}