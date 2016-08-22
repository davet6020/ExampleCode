@extends('layouts.admin')
@include('layouts.menu')

@section('content')
{!! Form::open(array('action' => 'RegisterController@login')) !!}
<div class="row">
  <div class="col-md-4">
    <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
      {!! Form::label('email', 'Email') !!}
      {!! Form::text('email', null, ['class' => 'form-control']) !!}
      {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
    </div>
    <div class=" form-group {{ ($errors->has('password')) ? 'has-error' : '' }}">
      {!! Form::label('password', 'Password') !!}
      {!! Form::password('password', ['class' => 'form-control']) !!}
      {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
    </div>
    {{ Form::submit('Login', ['class' => 'btn-lg btn-primary', 'id' => 'submitLoginForm']) }}<br>
    <a href="{{ $forgotUrl }}">I forgot my password</a>
  </div>
</div>
@stop
