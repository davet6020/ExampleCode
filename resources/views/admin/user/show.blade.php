@extends('layouts.admin')
@include('layouts.menu')

<h1>User</h1>
<p>{{ $user['email'] }}</p>
<p>{{ $user['first_name'] }}</p>
<p>{{ $user['last_name'] }}</p>
<p>{{ $user['company'] }}</p>
<p>{{ $user['has_terms'] }}</p>
<p>{{ $user['account_number'] }}</p>
