<div class="main-header col-md-12">

<button data-toggle="modal" data-target="#add_placetype_modal" class="add-button btn btn-primary" >{{trans('lang.add')}}</button>

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
				<th scope="col">{{trans('lang.name')}}</th>
				<th scope="col">{{trans('lang.marker')}}</th>
				<th scope="col">خيارات</th>
			</tr>
		</thead>
		<tbody>
			@foreach($placestypes as $item)
			<tr>
				<td>{{$item->name}}</td>
				<td><img class="profile-pic-small" src="{{$resource.'images/markers/'.$item->marker}}"/></td>
				<td>
					<button data-toggle="modal" data-target="#edit_placetype_{{$item->id}}_modal" class="btn btn-primary">{{trans('lang.edit')}}</button>

					<button onClick="deletePlaceType({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>

				</td>
			</tr>

			{{-- EDIT MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="edit_placetype_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{route('admin-edit-placetype')}}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<div class="form-froup">
									<label for="name">{{trans('lang.name')}}</label>
									<input type="hidden" class="form-control" name="placetype_id" value="{{$item->id}}">
									<input type="text" class="form-control" name="name" value="{{$item->name}}">
								</div>
								<div class="form-froup">
									<label for="file">{{trans('lang.marker')}}</label>
									<input type="file" class="form-control" name="image">
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-success">{{trans('lang.edit')}}</button>&nbsp;
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			{{-- END EDIT MODAL --}}
			@endforeach
		</tbody>
	</table>
</div>



{{-- ADD MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="add_placetype_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-add-placetype')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="form-froup">
						<label for="name">{{trans('lang.name')}}</label>
						<input type="text" class="form-control" name="name">
					</div>
					<div class="form-froup">
						<label for="file">{{trans('lang.marker')}}</label>
						<input type="file" class="form-control" name="image">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">{{trans('lang.add')}}</button>&nbsp;
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>
{{-- END ADD MODAL --}}


<script>
	 var token = '{{Session::token()}}'
  	var delete_placetype_url = '{{route("admin-delete-placetype")}}'
</script>