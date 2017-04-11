@if (Route::currentRouteName() === 'admin.roles.create')
  {!! Form::open(['route' => 'admin.roles.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
@else
  {!! Form::model($role, ['route' => ['admin.roles.update', $role->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
@endif 
<div class="box-body">
  <div class="form-group">
    <label class="col-sm-2 control-label">显示名称</label>
    <div class="col-sm-10">
      {!! Form::text('label', null, ['class' => 'form-control', 'placeholder' => '显示名称']) !!}
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">角色名</label>
    <div class="col-sm-10">
      {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '角色名']) !!}
    </div>
  </div>
</div>
<div class="box-footer text-right">
  <a class="btn btn-default" href="{{ URL::previous() }}">取消</a>
  @if (Route::currentRouteName() === 'admin.roles.create')
    <button type="submit" class="btn btn-primary">创建</button>
  @else
    <button type="submit" class="btn btn-primary">编辑</button>
  @endif 
</div>
{!! Form::close() !!}