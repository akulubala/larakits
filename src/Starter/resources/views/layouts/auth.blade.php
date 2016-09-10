<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ elixir('css/all.css') }}">
</head>
<body class="hold-transition login-page">
@yield('content')
<script src="{{ elixir('js/all.js') }}"></script>
@yield('js')
</body>
</html>

