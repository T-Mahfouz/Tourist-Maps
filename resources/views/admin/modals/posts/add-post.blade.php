<div class="modal" tabindex="-1" role="dialog" id="add_post_modal">
	<div class="modal-dialog" role="document" style="width: 75%;max-width: 75%;">
		<div class="modal-content">
			<div class="modal-header" style="direction: rtl;">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<form action="{{route('admin-add-post')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body" style="direction: rtl;text-align: right;">
					<div class="form-group">
						<label for="category_id">{{trans('lang.category')}}<b style="color:brown;">*</b></label>
						<select class="form-control" name="category_id">
							@foreach($categories as $category)
							<option value="{{$category->id}}">{{$category->name_ar}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="title">{{trans('lang.title')}}<b style="color:brown;">*</b></label>
						<input class="form-control" type="text" name="title">
					</div>
					<div class="form-group">
						<label for="content">{{trans('lang.content')}}<b style="color:brown;">*</b></label>
						<textarea rows="15" class="form-control" type="text" name="content"></textarea>
					</div>
					<div class="form-group">
						<label for="keywords">{{trans('lang.keywords')}}<b style="color:brown;">*</b></label>
						<textarea rows="5" class="form-control" type="text" name="keywords">رياضة، فن، محمد صلاح، الأهلى</textarea>
					</div>
					@if($admin->role->role == 'high')
					<div class="form-group">
						<label for="special">{{trans('lang.special')}}</label>
						<input id='special' class="" type="checkbox" name="special">
					</div>
					@endif
					<div class="form-group">
						<label for="image">{{trans('lang.image')}}</label>
						<input class="form-control" type="file" name="image">
					</div>
					<div class="form-group">
						<label for="video">{{trans('lang.video')}}</label>
						<input class="form-control" type="text" name="video" placeholder="{{trans('lang.video_link')}} ...">
					</div>
										
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
					<button type="submit" class="btn btn-primary" id="deleteMedicineButton">إضافة</button>
				</div>
			</form>
		</div>
	</div>
</div>