@extends('layouts.admin')
@include('layouts.menu')

<h1>List of all users</h1>

  @foreach ($users as $user)
  <div>
    <p>
      {{ $user['email'] }}
      {{ Form::open(['action' => array('RegisterController@destroy', $user['id']), 'method' => 'delete', 'class' => 'deleteForm']) }}
          <input type="submit" class="deleteBtn" value="delete" />
      {{ Form::close() }}
      {{ Form::open(['action' => array('RegisterController@edit', $user['id']), 'method' => 'get', 'class' => 'updateForm']) }}
          <input type="submit" class="updateBtn" value="update" />
      {{ Form::close() }}
    </p>
  </div>
  @endforeach


