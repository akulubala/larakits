@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            @include('admin.roles._search')
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                      <th>ID</th>
                      <th>角色名称</th>
                      <th>创建时间</th>
                      <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->created_at }}</td>
                                <td>
                                  <a class="btn btn-warning btn-xs pull-left" href="{{ url('admin/roles/'.$role->id.'/edit') }}"><span class="fa fa-edit"></span> Edit</a>
                                  @if ($role->id !== Auth::id())
                                    <form method="post" action="{!! url('admin/roles/'.$role->id) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button class="btn btn-danger col-md-offset-1 btn-xs pull-left" type="submit" onclick="return confirm('Are you sure you want to delete this role?')"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
@stop