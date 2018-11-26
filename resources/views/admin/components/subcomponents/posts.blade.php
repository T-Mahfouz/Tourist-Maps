<div class="main-header col-md-12">
<!-- <h3>باقات التميز</h3> -->
<form action="" method="get" style="right: 12%;">
  	
  	<input class="form-control" type="hidden" name="filter" value="{{Request::get('filter')}}">

  	@if($admin->role->role == 'high')
    <label for="admin">{{trans('lang.admin')}}</label>
    <select class="form-control" style="width: auto;display: inline;" name="admin" id="admin">
    	<option {{(Request::get('admin') == 0)?'selected':''}} value="0">{{trans('lang.all')}}</option>
      @foreach($admins as $item)
      <option {{(Request::get('admin') == $item->id)?'selected':''}} value="{{$item->id}}">{{$item->name}}</option>
      @endforeach
    </select>
	@endif

    <label for="category">{{trans('lang.category')}}</label>
    <select class="form-control" style="width:auto;display:inline;" name="category">
      <option {{(!Request::get('category'))?'selected':''}} value="0">الكل</option>
      @foreach($categories as $category)
      <option {{(Request::get('category') == $category->id)?'selected':''}} value="{{$category->id}}">{{$category->name_ar}}</option>
      @endforeach
    </select>
    

    <label for="title">{{trans('lang.search_title')}}</label>
    <input class="form-control" style="width: 30%;display: inline;" type="text" placeholder="{{trans('lang.search_title')}}" name="title" value="{{Request::get('title')}}">


  <input class="btn btn-success" type="submit" value="{{trans('lang.search')}}">
</form>
<select class="form-control" name="filter" id="filter-posts" style="position:absolute;bottom: 10px;right: 86%;width: 12%;
">
	<option {{Request::get('filter') == 'old' ? 'selected':''}} value="old">الأقدم</option>
	<option {{Request::get('filter') == 'new' ? 'selected':''}} value="new">الأحدث</option>
	<option {{Request::get('filter') == 'mostview' ? 'selected':''}} value="mostview">الأكثر مشاهدة</option>
</select>
<button onclick="addPost()" class="add-button btn btn-primary">
	<i class="fa fa-check"></i> {{trans('lang.add_post')}}
</button>

</div>

<div class="main-body col-md-12">

	@if(session()->has('feedback'))
	<div class="alert alert-info">
		{{session()->get('feedback')}}
	</div>
	@endif
	
	

	<table class="table table-hover">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">{{trans('lang.title')}}</th>
				<th scope="col">{{trans('lang.content')}}</th>
				<th scope="col">{{trans('lang.views')}}</th>
				<th scope="col">{{trans('lang.comments')}}</th>
				<th scope="col">{{trans('lang.category')}}</th>
				<th scope="col">{{trans('lang.created_at')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>
			
			@foreach($posts as $index=>$item)
			
			<tr>
				<th scope="row">{{(Request::get('page') * 5 + $index+1)-5}}</th>
				<td>{{mb_substr($item->title,0,25,"utf-8").'...'}}</td>
				<td>
					@if(strlen($item->content) > 25)
						{{str_replace('<br />','',mb_substr($item->content,0,25,"utf-8")).'...'}}
					@else
						{{$item->content}}
					@endif
				</td>
				<td>{{$item->views}}</td>
				<td>{{count($item->comments)}}</td>
				<td>{{$item->category->name_ar}}</td>
				<td>{{$item->created_at->diffForHumans()}}</td>
				<td>
					<button onclick="viewPost({{$item}},{{$item->comments}},{{$item->admin}})" class="btn btn-primary">مشاهدة</button>
					<button onclick="addComment({{$item->id}})" class="btn btn-primary bg-aqua">{{trans('lang.add_comment')}}</button>
					<button onclick="editPost({{$item}})" class="btn btn-primary bg-green">{{trans('lang.edit')}}</button>
					<button onclick="deletePost({{$item->id}})" class="btn btn-secondary">{{trans('lang.delete')}}</button>

				</td>
			</tr>
			@endforeach
			
		</tbody>
	</table>
	{{ $posts->links() }}
</div>




<script>
	
	var currentPage = "{{ Request::get('page') }}";
	var title = "{{ Request::get('title') }}";
	var category = "{{ Request::get('category') }}";
	var adminParam = "{{ Request::get('admin') }}";

	var adminPostsPath = '{{route("admin-posts")}}';
	var deleteCommentPath = '{{route("admin-delete-comment")}}';
	var token = "{{ Session::token() }}";

	var userProfilePath = "{{$resource.'images/users/'}}";
	var adminProfilePath = "{{$resource.'images/users/'}}";
	var postImagePath = "{{$resource.'images/posts/'}}";
</script>


@include('admin.modals.posts.add-post')
@include('admin.modals.posts.add-comment')
@include('admin.modals.posts.edit-post')
@include('admin.modals.posts.view-post')
@include('admin.modals.posts.delete-post')
@include('admin.modals.posts.delete-comment')
