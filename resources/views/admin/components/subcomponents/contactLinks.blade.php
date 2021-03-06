<div class="main-header col-md-12">


	<button data-toggle='modal' data-target='#add_contactLink_modal' class="add-button btn btn-primary">
		إضافة وسيلة تواصل جديدة
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
				<th scope="col">{{trans('lang.contact_type')}}</th>
				<th scope="col">{{trans('lang.icon')}}</th>
				<th scope="col">{{trans('lang.link')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($contactLinks as $index => $item)
			<tr>
				<td>{{$index+1}}</td>
				<td>{{$item->type}}</td>
				<td><img class="profile-pic-small" src="{{$resource.'images/contactlinks/'.$item->icon}}"/></td>
				<td>{{$item->link}}</td>

				<td>
					<button data-toggle='modal' data-target='#edit_contactLink_{{$item->id}}_modal' class="btn btn-primary">{{trans('lang.edit')}}</button>

					<button onclick="deleteContactLink({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>

				</td>
			</tr>

			{{-- EDIT MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="edit_contactLink_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{route('admin-edit-contactLink')}}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<div class="form-froup">
									<label for="title">{{trans('lang.contact_type')}}</label>
									<input type="text" class="form-control" name="type" value="{{$item->type}}">
								</div>

								<div class="form-froup">
									<label for="link">{{trans('lang.link')}}</label>
									<input type="text" class="form-control" name="link" value="{{$item->link}}">
								</div>

								<div class="form-froup">
									<label for="title">{{trans('lang.image')}}</label>
									<input type="hidden" name="contactLink_id" value="{{$item->id}}">
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


{{-- EDIT MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="add_contactLink_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-add-contactLink')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="form-froup">
						<label for="title">{{trans('lang.contact_type')}}</label>
						<input type="text" class="form-control" name="type">
					</div>

					<div class="form-froup">
						<label for="link">{{trans('lang.link')}}</label>
						<input type="text" class="form-control" name="link">
					</div>

					<div class="form-froup">
						<label for="title">{{trans('lang.image')}}</label>
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
{{-- END EDIT MODAL --}}


<script>
	var delete_contactlink_url = '{{route("admin-delete-contactLink")}}'
	var token = '{{Session::token()}}'
</script>


