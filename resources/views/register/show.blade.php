@extends('layouts.admin')
@include('layouts.menu')

<h1>User</h1>
<p>{{ $user['email'] }}</p>
<p>{{ $user['first_name'] }}</p>
<p>{{ $user['last_name'] }}</p>
<p>{{ $user['company'] }}</p>
<p>{{ $user['cell_phone'] }}</p>
<p>{{ $user['home_phone'] }}</p>
<p>{{ $user['work_phone'] }}</p>

<hr/>
Billing Address <br>
@if(isset($billing))
  Type: {{ $billing['type'] }} <br>
  Full_name: {{ $billing['full_name'] }} <br>
  Name_on_cc: {{ $billing['name_on_cc'] }} <br>
  Address_1: {{ $billing['address_1'] }} <br>
  Address_2: {{ $billing['address_2'] }} <br>
  City: {{ $billing['city'] }} <br>
  State: {{ $billing['state'] }} <br>
  Zip: {{ $billing['zip'] }} <br>
  Country: {{ $billing['country'] }} <br>
@endif


<hr/>
Company Address <br>
@if(isset($company))
  Type: {{ $company['type'] }} <br>
  Full_name: {{ $company['full_name'] }} <br>
  Name_on_cc: {{ $company['name_on_cc'] }} <br>
  Address_1: {{ $company['address_1'] }} <br>
  Address_2: {{ $company['address_2'] }} <br>
  City: {{ $company['city'] }} <br>
  State: {{ $company['state'] }} <br>
  Zip: {{ $company['zip'] }} <br>
  Country: {{ $company['country'] }} <br>
@endif


<hr/>
Shipping Address <br>
@if(isset($shipping))
  Type: {{ $shipping['type'] }} <br>
  Full_name: {{ $shipping['full_name'] }} <br>
  Name_on_cc: {{ $shipping['name_on_cc'] }} <br>
  Address_1: {{ $shipping['address_1'] }} <br>
  Address_2: {{ $shipping['address_2'] }} <br>
  City: {{ $shipping['city'] }} <br>
  State: {{ $shipping['state'] }} <br>
  Zip: {{ $shipping['zip'] }} <br>
  Country: {{ $shipping['country'] }} <br>
@endif
