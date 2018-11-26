<div class="modal" tabindex="-1" role="dialog" id="add_comment_modal">
	<div class="modal-dialog" role="document" style="width: 75%;max-width: 75%;">
		<div class="modal-content">
			<div class="modal-header" style="direction: rtl;">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<form action="{{route('admin-add-comment')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body" style="direction: rtl;text-align: right;">
					<input id="post-add-comment-id" type="hidden" name="post_id">
					<div class="form-group">
						<label for="content">{{trans('lang.content')}}<b style="color:brown;">*</b></label>
						<textarea rows="15" class="form-control" type="text" name="content"></textarea>
					</div>
					<div class="form-group">
						<label for="image">{{trans('lang.image')}}</label>
						<input class="form-control" type="file" name="image">
					</div>										
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('lang.cancel')}}</button>
					<button type="submit" class="btn btn-primary" id="deleteMedicineButton">{{trans('lang.add')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>