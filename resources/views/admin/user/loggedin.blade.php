@extends('layouts.admin')
@include('layouts.menu')

<h1>Hello {{$email}}.  You are activated.</h1>

  <div>
    <p>
      {{ Form::open(['action' => array('UsersController@destroy', $id), 'method' => 'delete', 'class' => 'deleteForm']) }}
          <input type="submit" class="deleteBtn" value="delete" />
      {{ Form::close() }}
      {{ Form::open(['action' => array('UsersController@edit', $id), 'method' => 'get', 'class' => 'updateForm']) }}
          <input type="submit" class="updateBtn" value="update" />
      {{ Form::close() }}
      {{ Form::open(['action' => array('UsersController@logout'), 'method' => 'post', 'class' => 'logoutForm']) }}
          <input type="submit" class="logoutBtn" value="logout" />
      {{ Form::close() }}
    </p>
  </div>
