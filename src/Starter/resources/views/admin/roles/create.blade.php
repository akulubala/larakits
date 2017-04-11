@extends('layouts.admin')
@section('content')
@include('partials.errors')
<div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">新建角色信息</h3>
    </div>
    @include('admin.roles._form')
</div>
@stop