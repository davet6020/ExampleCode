
<div class='mainmenu'>
  <ul>
    <li class='active'><a href='/'><span>Home</span></a></li>
    <li><a href='/user/admin'><span>Admin Panel</span></a>
      <ul class="">
        <li><a href="/user/create">Create New User</a></li>
        <li><a href="/user/find">Edit A User</a></li>
      </ul>
    </li>
    <li><a href='#'><span>Bill of Materials</span></a>
      <ul class="">
        <li><a href="/importFile">Import BOM</a></li>
      </ul>
    </li>

      @if(isset($loggedIn))
        @if($loggedIn)
          <li class='last' style="float:right"><a href='/register/logout'><span>Logout</span></a></li>
            <ul class="">
              <li class='last' style="float:right"><a href='profile'><span>Profile</span></a></li>
            </ul>
          </li>
        @endif
      @else
          <li class='last' style="float:right"><a href='/register/login'><span>Login</span></a>
            <ul class="">
              <li class='last' style="float:right"><a href='/register/pwforgot'><span>Forgot Password</span></a></li>
              <li class='last' style="float:right"><a href='/register/create'><span>Register</span></a></li>
            </ul>
          </li>
      @endif
      
  </ul>
</div>
<br/>