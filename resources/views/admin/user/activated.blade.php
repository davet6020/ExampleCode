@extends('layouts.admin')
@include('layouts.menu')

<h1>User {{$email}} was just created.</h1>
<p>{{$body}}</p>

<div>
@extends('layouts.admin')

{!! Form::open(array('action' => 'UsersController@loginTest')) !!}
    Email: {!! Form::text('email') !!} <br>
    Password: {!! Form::password('password') !!} <br>
    {!! Form::submit('Login') !!}
{!! Form::close() !!}
</div>