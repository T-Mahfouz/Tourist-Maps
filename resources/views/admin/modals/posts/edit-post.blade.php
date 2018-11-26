<div class="modal" tabindex="-1" role="dialog" id="edit_post_modal">
	<div class="modal-dialog" role="document" style="width: 70%;max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-edit-post')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<input id="post-edit-id" type="hidden" name="post_id">
					<div class="form-group">
						<label for="category_id">{{trans('lang.category')}}<b style="color:brown;">*</b></label>
						<select id="post-edit-category" class="form-control" name="category_id">
							@foreach($categories as $category)
							<option value="{{$category->id}}">{{$category->name_ar}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-froup">
						<label for="title">{{trans('lang.title')}}<b style="color:brown;">*</b></label>
						<input id="post-edit-title" type="text" class="form-control" name="title">
					</div>
					<div class="form-froup">
						<label for="content">{{trans('lang.content')}}<b style="color:brown;">*</b></label>
						<textarea rows="15" id="post-edit-content" type="text" class="form-control" name="content"></textarea>
					</div>
					<div class="form-group">
						<label for="keywords">{{trans('lang.keywords')}}<b style="color:brown;">*</b></label>
						<textarea rows="5" id="post-edit-keywords" class="form-control" type="text" name="keywords"></textarea>
					</div>

					@if($admin->role->role == 'high')
					<div class="form-group">
						<label for="post-edit-special">{{trans('lang.special')}}</label>
						<input id='post-edit-special' class="" type="checkbox" name="special">
					</div>
					@endif
					<div class="form-group">
						<label for="image">{{trans('lang.change-image')}}</label>
						<input id="post-edit-image" class="form-control" type="file" name="image">
					</div>
					<div class="form-group">
						<label for="video">{{trans('lang.video')}}</label>
						<input id="post-edit-video" class="form-control" type="text" name="video" placeholder="{{trans('lang.video_link')}} ...">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
					<button type="submit" class="btn btn-success">{{trans('lang.edit')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>