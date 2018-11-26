<div class="main-header col-md-12">

	<button data-toggle='modal' data-target='#add_slider_modal' class="add-button btn btn-primary">
		إضافة صورة جديدة
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
				<th scope="col">{{trans('lang.link')}}</th>
				<th scope="col">{{trans('lang.image')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($sliders as $index => $item)
			<tr>
				<td>{{$index+1}}</td>
				<td>{{$item->link}}</td>
				<td><img style="height: 200px;width: 50%;" src="{{$resource.'images/sliders/'.$item->image}}" alt=""></td>
				<td>
					<button style="margin: 5px;" data-toggle='modal' data-target='#edit_slider_{{$item->id}}_modal' class="btn btn-primary">{{trans('lang.edit')}}</button>

					<button style="margin: 5px;" onClick="deleteSlider({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>
				</td>
			</tr>

			{{-- EDIT MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="edit_slider_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{route('admin-edit-slider')}}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<div class="form-froup">
									<img style="height: 250px;width: 100%;" src="{{$resource.'images/sliders/'.$item->image}}" alt="">
								</div>
								<div class="form-froup">
									<label for="link">{{trans('lang.link')}}</label>
									<input class="form-control" type="text" name="link" value="{{$item->link}}">
								</div>
								<div class="form-froup">
									<label for="title">{{trans('lang.image')}}</label>
									<input type="hidden" name="slider_id" value="{{$item->id}}">
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
<div class="modal" tabindex="-1" role="dialog" id="add_slider_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-add-slider')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">

					<div class="form-froup">
						<label for="link">{{trans('lang.link')}}</label>
						<input class="form-control" type="text" name="link">
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
{{-- END ADD MODAL --}}



<script>
	var delete_slider_url = '{{route("admin-delete-slider")}}'
	var token = '{{Session::token()}}'
</script>