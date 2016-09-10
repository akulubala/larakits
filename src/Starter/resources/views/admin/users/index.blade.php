@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            @include('admin.users._search')
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                      <th>ID</th>
                      <th>用户名</th>
                      <th>邮箱</th>
                      <th>创建时间</th>
                      <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>
                                  <a class="btn btn-warning btn-xs pull-left" href="{{ url('admin/users/'.$user->id.'/edit') }}"><span class="fa fa-edit"></span> Edit</a>
                                  @if ($user->id !== Auth::id())
                                    <form method="post" action="{!! url('admin/users/'.$user->id) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button class="btn btn-danger col-md-offset-1 btn-xs pull-left" type="submit" onclick="return confirm('Are you sure you want to delete this user?')"><i class="fa fa-trash"></i> Delete</button>
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
