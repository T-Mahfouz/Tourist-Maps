<div class="modal" tabindex="-1" role="dialog" id="delete_comment_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" style="direction: rtl;">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="direction: rtl;text-align: right;">
				هل تريد حذف هذا التعليق؟
				<input type="hidden" name="comment_id" id="delete_comment_id" value="">

				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('lang.cancel')}}</button>
				<button type="button" class="btn btn-primary" onclick="deleteComment($('#delete_comment_id').val())">{{trans('lang.accept')}}</button>
			</div>
			
		</div>
	</div>
</div>