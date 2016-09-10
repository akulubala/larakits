@extends('layouts.auth')
@section('content')
<div class="login-box">
      <div class="login-logo">
            <a<b>Admin</b>LTE</a>
      </div>
          <!-- /.login-logo -->
      <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form action="{{ route('login.post') }}" method="post">
              {!! csrf_field() !!}
              <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="row">
                <div class="col-xs-8">
                  <div class="checkbox icheck">
                    <label>
                      <input type="checkbox" name="remember"> Remember Me
                    </label>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
              </div>
            </form>
            <a href="/password/email">I forgot my password</a>
      </div>
</div>
@stop
@section('js')
<script type="text/javascript">
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
@stop