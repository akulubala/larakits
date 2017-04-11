<div class="box-header with-border">
      <h3 class="box-title">所有用户</h3>
      <div class="box-tools">
         <form id="" class="pull-right raw-margin-top-24 raw-margin-left-24" method="get" action="{{ route('admin.roles.search.get') }}">
         	{!! csrf_field() !!}
	        <div class="input-group input-group-sm" style="width: 200px;">
	          <input type="text" name="search" class="form-control pull-right" placeholder="角色户名">
	          <div class="input-group-btn">
	            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
	          </div>
	        </div>
        </form>
      </div>
</div>