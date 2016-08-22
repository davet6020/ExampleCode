@extends('layouts.admin')
@include('layouts.menu')

{!! Form::model($user, array('route' => array('user.update', $user->id), 'method' => 'put')) !!}
    First Name: {!! Form::text('first_name') !!} <br>
    Last Name: {!! Form::text('last_name') !!} <br>
    Email: {!! Form::text('email') !!} <br>
    Password: {!! Form::password('password') !!} <br>
    Company: {!! Form::text('company') !!} <br>
    
    @if($user->has_terms === 1)
      Has Terms: {!! Form::select('has_terms', array('1' => 'Yes', '0' => 'No'), '1') !!} <br/>
    @else
      Has Terms: {!! Form::select('has_terms', array('0' => 'No', '1' => 'Yes'), '0') !!} <br/>
    @endif

    Account Number: {!! Form::text('account_number') !!} <br>

    {!! Form::submit('Update') !!}
{!! Form::close() !!}

