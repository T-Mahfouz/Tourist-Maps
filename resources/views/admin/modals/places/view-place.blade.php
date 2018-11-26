<div class="modal" tabindex="-1" role="dialog" id="view_place_modal">
	<div class="modal-dialog" role="document" style="width: 75%;max-width: 75%;">
		<div class="modal-content">
			<div class="modal-header" style="direction: rtl;">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="direction: rtl;text-align: right;">
				<div class="form-group" style="border-bottom:1px solid #ccc;">
					<label for=""><h5 style="color:brown;">{{trans('lang.name')}}</h5 style="color:brown;"></label>
					<p id="view_place_name"></p>
				</div>

				<div class="form-group" style="border-bottom:1px solid #ccc;">
					<label for=""><h5 style="color:brown;">{{trans('lang.placetype')}}</h5 style="color:brown;"></label>
					<p id="view_place_type"></p>
				</div>
				<div class="form-group" style="border-bottom:1px solid #ccc;">
					<label for=""><h5 style="color:brown;">{{trans('lang.booking_link')}}</h5 style="color:brown;"></label>
					<p id="view_booking_link"></p>
				</div>

				<div class="form-group" style="border-bottom:1px solid #ccc;">
					<label for=""><h5 style="color:brown;">{{trans('lang.continent')}}</h5 style="color:brown;"></label>
					<p id="view_place_continent"></p>
				</div>
				<div class="form-group" style="border-bottom:1px solid #ccc;">
					<label for=""><h5 style="color:brown;">{{trans('lang.country')}}</h5 style="color:brown;"></label>
					<p id="view_place_country"></p>
				</div>
				<div class="form-group" style="border-bottom:1px solid #ccc;">
					<label for=""><h5 style="color:brown;">{{trans('lang.geolocation')}}</h5></label>
					<p id="view_place_geolocation"></p>
				</div>
				<div class="form-group" style="border-bottom:1px solid #ccc;">
					<label for=""><h5 style="color:brown;">{{trans('lang.address')}}</h5></label>
					<p id="view_place_address"></p>
				</div>
				<div class="form-group" style="border-bottom:1px solid #ccc;">
					<label for=""><h5 style="color:brown;">{{trans('lang.description')}}</h5 style="color:brown;"></label>
					<p style="word-wrap: break-word;"><strong id="view_place_description"></strong></p>
				</div>
				<div class="form-group">
					<label for=""><h5 style="color:brown;">{{trans('lang.images')}}</h5 style="color:brown;"></label>
					<div id="view_place_images"></div>
				</div>
														
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('lang.close')}}</button>
			</div>
		</div>
	</div>
</div>