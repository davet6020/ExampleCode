@extends('layouts.admin')
@include('layouts.menu')

<h1>Hello {{ $email }}.   Activate your account by clicking the link below.</h1>
<a href="{{ $activationUrl }}">Activate your account</a><br/><br/>
