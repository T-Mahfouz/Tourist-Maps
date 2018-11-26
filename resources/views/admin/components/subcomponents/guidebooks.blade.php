<div class="main-header col-md-12">


	<button class="add-button btn btn-primary" data-toggle='modal' data-target='#add_guidebook_modal'>{{trans('lang.add_gudiebook')}}</button>



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
				<th scope="col">{{trans('lang.path')}}</th>
				<th scope="col">{{trans('lang.image')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>
			
			@foreach($guidebooks as $index=>$item)
			<tr>
				<th scope="row">{{$index+1}}</th>
				<td>{{$item->title}}</td>
				<td><p style="word-wrap: break-word;">{{$item->content}}</p></td>
				<td>{{$item->path}}</td>
				<td><img style="height: 70px;width: 130px;margin: 5px;" src="{{$resource.'images/books/'.$item->image}}"/></td>
				<td>
					<button style="margin-top: 5px;" data-toggle='modal' data-target='#edit_guidebook_{{$item->id}}_modal' class="btn btn-primary">{{trans('lang.edit')}}</button>
					<button style="margin-top: 5px;" onclick="deleteGuidebook({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>
				</td>
			</tr>
			{{-- EDIT MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="edit_guidebook_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{route('admin-edit-guidebook')}}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<div class="form-froup">
									<label for="title">{{trans('lang.title')}}</label>
									<input type="hidden" name="guidebook_id" value="{{$item->id}}">
									<input type="text" class="form-control" name="title" value="{{$item->title}}">
								</div>
								<div class="form-froup">
									<label for="content">{{trans('lang.content')}}</label>
									<textarea type="text" class="form-control" name="content">{{$item->content}}</textarea>
								</div>
								<div class="form-froup">
									<label for="image">{{trans('lang.image')}}</label>
									<input type="file" class="form-control" name="image">
								</div>
								<div class="form-froup">
									<label for="link">{{trans('lang.book')}}</label>
									<input type="file" class="form-control" name="book" accept="application/pdf">
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
	{{ $guidebooks }}
</div>



{{-- ADD MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="add_guidebook_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-add-guidebook')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="form-froup">
						<label for="title">{{trans('lang.title')}}</label>
						<input type="text" class="form-control" name="title">
					</div>
					<div class="form-froup">
						<label for="content">{{trans('lang.content')}}</label>
						<textarea type="text" class="form-control" name="content"></textarea>
					</div>
					<div class="form-froup">
						<label for="image">{{trans('lang.image')}}</label>
						<input type="file" class="form-control" name="image">
					</div>
					<div class="form-froup">
						<label for="link">{{trans('lang.book')}}</label>
						<input type="file" class="form-control" name="book" accept="application/pdf">
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
	var delete_guidebook_url = '{{route("admin-delete-guidebook")}}'
</script>


