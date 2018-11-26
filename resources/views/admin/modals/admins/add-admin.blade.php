<div class="modal" tabindex="-1" role="dialog" id="add_admin_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-add-admin')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">

					<div class="form-group">
						<label for="name">{{trans('lang.name')}}</label>
						<input type="text" class="form-control" name="name" placeholder="{{trans('lang.name')}}">
					</div>
					<div class="form-group">
						<label for="email">{{trans('lang.email')}}</label>
						<input type="text" class="form-control" name="email" placeholder="{{trans('lang.email')}}">
					</div>
					<div class="form-group">
						<label for="status">{{trans('lang.status')}}</label>
						<select type="text" class="form-control" name="status">
							<option value="0">{{trans('lang.notactive')}}</option>
							<option value="1">{{trans('lang.active')}}</option>
						</select>
					</div>
					<div class="form-group">
						<label for="role_id">{{trans('lang.role')}}</label>
						<select type="text" class="form-control" name="role_id">
							<option value="1">low</option>
							<option value="2">high</option>
						</select>
					</div>
					<div class="form-group">
						<label for="categories[]">{{trans('lang.categories')}}</label>
						<select class="form-control" multiple name="categories[]">
						</select>
					</div>
					<div class="form-group">
						<label for="image">{{trans('lang.image')}}</label>
						<input type="file" name="image">
					</div>
					<div class="form-group">
						<label for="password">{{trans('lang.password')}}</label>
						<input type="password" class="form-control" name="password" placeholder="{{trans('lang.password')}}">
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