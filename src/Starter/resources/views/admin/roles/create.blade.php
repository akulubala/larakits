@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>Role Admin: Create</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('admin.roles._form')
        </div>
    </div>

@stop