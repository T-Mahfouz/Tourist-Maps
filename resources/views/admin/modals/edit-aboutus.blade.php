<div class="modal" tabindex="-1" role="dialog" id="edit_aboutus_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-edit-aboutus')}}" method="post">
				@csrf
				<div class="modal-body">
					<div class="form-froup">
						<label for="title">{{trans('lang.title')}}</label>
						<input id="aboutus-edit-title" type="text" class="form-control" name="title">
					</div>
					<div class="form-froup">
						<label for="content">{{trans('lang.content')}}</label>
						<textarea rows="5" id="aboutus-edit-content" type="text" class="form-control" name="content"></textarea>
					</div>

				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">{{trans('lang.edit')}}</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>