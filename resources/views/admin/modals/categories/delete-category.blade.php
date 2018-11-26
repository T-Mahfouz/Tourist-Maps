<div class="modal" tabindex="-1" role="dialog" id="delete_categoy_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<form action="{{route('admin-delete-category')}}" method="post">
				@csrf
				<div class="modal-body">
					هل تريد حذف هذا التصنيف وجميع المقالات الخاصة به ؟ 
					<div class="form-froup">
						<input type="hidden" name="category_id" id="category-delete-id">
					</div>

				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">حذف</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
				</div>
			</form>
		</div>
	</div>
</div>