<div class="modal" tabindex="-1" role="dialog" id="delete_post_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" style="direction: rtl;">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<form action="{{route('admin-delete-post')}}" method="post">
				@csrf
				<div class="modal-body" style="direction: rtl;text-align: right;">
					هل ترغب فى حذف المقال وجميع الردود الخاصة به؟
					<div class="form-group">
						<input type="hidden" name="post_id" id="delete_post_id" value="">
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('lang.cancel')}}</button>
					<button type="submit" class="btn btn-primary" id="deleteMedicineButton">{{trans('lang.accept')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>