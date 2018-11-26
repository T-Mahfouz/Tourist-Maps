<div class="main-header col-md-12">

<form action="" method="get">
  
    <label for="status">{{trans('lang.status')}}</label>
    <select class="form-control" style="width: auto;display: inline;" name="status" id="status">
      <option {{(Request::get('status') == 0)?'selected':''}} value="0">غير مفعل</option>
      <option {{(Request::get('status') == 1)?'selected':''}} value="1">مفعل</option>
      <option {{!Request::has('status')|| (!in_array(Request::get('status'), [0,1]))?'selected':''}} value="2">الكل</option>
    </select>

  <input class="btn btn-success" type="submit" value="{{trans('lang.search')}}">
</form>

@if(Auth::guard('admin')->user()->role->role == 'high')
<button onclick="addCategory()" class="add-button btn btn-primary" style="bottom: 70px;">
 إضافة  تصنيف جديد
</button>
@endif



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
				<th scope="col">الاسم باالعربى</th>
				<th scope="col">الاسم بالانجليزى</th>
				<th scope="col">الحالة</th>
				<th scope="col">عدد المقالات</th>
				@if(Auth::guard('admin')->user()->role->role == 'high')
				<th scope="col">عدد الكتاب</th>
				<th scope="col">خيارات</th>
				@endif
			</tr>
		</thead>
		<tbody>
			
			@foreach($categories as $index=>$item)
			<tr>
				<th scope="row">{{$index+1}}</th>
				<td>{{$item->name_ar}}</td>
				<td>{{$item->name_en}}</td>
				<td><span class="badge badge-{{$item->active?'primary':'dark'}}">{{$item->active?trans('lang.active'):trans('lang.notactive')}}</span></td>
				<td>{{count($item->category_posts)}}</td>
				
				@if(Auth::guard('admin')->user()->role->role == 'high')
				<td>{{count($item->category_writes)}}</td>
				
				<td>
					<button onclick="editCategory({{$item}})" class="btn btn-primary">{{trans('lang.edit')}}</button>

					<button onclick="deleteCategory({{$item}})" class="btn btn-primary bg-green">{{trans('lang.delete')}}</button>

				</td>
				@endif
			</tr>
			@endforeach
			
		</tbody>
	</table>
	{{$categories->links()}}
</div>



@include('admin.modals.categories.add-category')
@include('admin.modals.categories.edit-category')
@include('admin.modals.categories.delete-category')


