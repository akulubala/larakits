@extends('layouts.admin')
@section('content')
@include('partials.errors')
<div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">邀请用户</h3>
    </div>
    @include('admin.users._form')
</div>
@stop