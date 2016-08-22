@extends('layouts.admin')
@section('content')
    <h3>Admin Settings Home</h3>
    @if (Session::has('error'))
        <p class="text-danger">{{ Session::get('error') }}</p>
    @endif
    @if ($data)
        <a href="{{ URL::to('/admin/settings/' . $data['id'] . '/edit') }}">Edit Active Settings</a>
    @else
        <a href="{{ URL::to('/admin/settings/create') }}">Create Settings</a>
    @endif
@stop