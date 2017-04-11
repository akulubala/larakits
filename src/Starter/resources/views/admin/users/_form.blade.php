@if (Route::currentRouteName() === 'admin.users.create')
  {!! Form::open(['route' => 'admin.users.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
@else
  {!! Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
@endif 
<div class="box-body">
  <div class="form-group">
    <label class="col-sm-2 control-label">邮箱</label>
    <div class="col-sm-10">
      {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => '邮箱']) !!}
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">姓名</label>
    <div class="col-sm-10">
      {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '姓名']) !!}
    </div>
  </div>
  <div class="form-group">
        <label class="col-sm-2 control-label">角色</label>
        <div class="col-sm-10">
        {!! Form::select('roles[]', $roles->pluck('name', 'id'), isset($selectedRoles) ? $selectedRoles : null, ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
        </div>
  </div>
</div>
<div class="box-footer text-right">
  <a class="btn btn-default" href="{{ URL::previous() }}">取消</a>
  @if (Route::currentRouteName() === 'admin.users.create')
    <button type="submit" class="btn btn-primary">邀请</button>
  @else
    <button type="submit" class="btn btn-primary">更新</button>
  @endif 
</div>
{!! Form::close() !!}