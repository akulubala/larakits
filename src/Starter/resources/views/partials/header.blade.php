<header class="main-header">
  <a href="/" class="logo">
    {{ env('APP_NAME') }}
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ url('img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
            <span class="hidden-xs">{{ Auth::user()->name }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <img src="{{ url('img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
              <p>
                {{ Auth::user()->name }} -—
                @forelse (Auth::user()->roles as $role)
                    {{ $role->name }}
                    @if (!$loop->last)
                      ,
                    @endif
                @empty
                    No Roles
                @endforelse                
                <small>用户注册于 {{ Auth::user()->created_at->toDateString() }}</small>
              </p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
              </div>
              <div class="pull-right">
                {!! Form::open(['route' => 'logout', 'method' => 'post']) !!}
                  <button type='submit' class="btn btn-default btn-flat">退出登录</button>
                {!! Form::close() !!}
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>