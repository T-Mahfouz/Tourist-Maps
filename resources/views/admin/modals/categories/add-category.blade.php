<div class="modal" tabindex="-1" role="dialog" id="add_categoy_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-add-category')}}" method="post">
				@csrf
				<div class="modal-body">
					<div class="form-group">
						<label for="name_ar">{{trans('lang.name_ar')}}</label>
						<input id="category-add-name_ar" type="text" class="form-control" name="name_ar" placeholder="{{trans('lang.name_ar')}}">
					</div>
					<div class="form-group">
						<label for="name_en">{{trans('lang.name_en')}}</label>
						<input id="category-add-name_en" type="text" class="form-control" name="name_en" placeholder="{{trans('lang.name_en')}}">
					</div>
					<div class="form-group">
						<label for="status">{{trans('lang.status')}}</label>
						<select id="category-add-status" type="text" class="form-control" name="status">
							<option value="0">{{trans('lang.notactive')}}</option>
							<option value="1">{{trans('lang.active')}}</option>
						</select>
					</div>

				</div>
				<div class="modal-footer float-right">
					<button type="submit" class="btn btn-success">{{trans('lang.add')}}</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>