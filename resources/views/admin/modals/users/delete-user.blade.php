<div class="modal fade" tabindex="-1" role="dialog" id="delete_user_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header2">
				 
			</div>
			
			<form action="{{route('admin-delete-user')}}" method="post">
				@csrf
				<div class="modal-body" style="direction: ltr;text-align: left;">
					هل تريد حذف هذا المستخدم؟

						<input type="hidden" name="user_id" id="delete_user_id" value="">				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">لا</button>
					<button type="submit" class="btn btn-primary" id="deleteMedicineButton">نعم</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->