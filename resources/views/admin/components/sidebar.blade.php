<aside>

	<div class="admin-pic_name col-md-12">
		<div class="col-md-4 offset-md-4">
			<img data-toggle='modal' data-target='#edit_admin_profile_modal' style="cursor: pointer;" class="pull-right" src="{{$resource.'images/users/'.Auth::guard('admin')->user()->image}}"/>
		</div>
		<div class="col-md-8 offset-md-2">
			<h4 class="pull-right">{{Auth::guard('admin')->user()->name}}</h4>
		</div>

	</div>
	<div class="clearfix"></div>
	<div class="navigation col-md-12">

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-home') active @endif" href="{{route('admin-home')}}">{{trans('lang.home')}} <i class="fa fa-home"></i></a>



		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-admins') active @endif" href="{{route('admin-admins')}}">{{trans('lang.admins')}} <i class="fa fa-black-tie"></i></a>
		
		
		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-users') active @endif" href="{{route('admin-users')}}">{{trans('lang.users')}} <i class="fa fa-users"></i></a>
		
		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-continents') active @endif" href="{{route('admin-continents')}}">{{trans('lang.continents')}}  <i class="fa fa-globe"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-countries') active @endif" href="{{route('admin-countries')}}">{{trans('lang.countries')}}  <i class="fa fa-flag-o"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-places') active @endif" href="{{route('admin-places')}}">{{trans('lang.places')}}  <i class="fa fa fa-map-marker"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-placestypes') active @endif" href="{{route('admin-placestypes')}}">{{trans('lang.placestypes')}}  <i class="fa fa-map-marker"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-aboutus') active @endif" href="{{route('admin-aboutus')}}">{{trans('lang.aboutus')}}  <i class="fa fa-question-circle"></i></a>

		<!-- <a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-intro') active @endif" href="{{route('admin-intro')}}">{{trans('lang.intro')}}  <i class="fa fa-map"></i></a> -->

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-offers') active @endif" href="{{route('admin-offers')}}">{{trans('lang.offers')}}  <i class="fa fa-gift"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-sliders') active @endif" href="{{route('admin-sliders')}}">{{trans('lang.sliders')}}  <i class="fa fa-sliders"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-guidebooks') active @endif" href="{{route('admin-guidebooks')}}">{{trans('lang.guidebooks')}}  <i class="fa fa-book"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-contactlinks') active @endif" href="{{route('admin-contactlinks')}}">{{trans('lang.contactlinks')}}  <i class="fa fa-link"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-externallinks') active @endif" href="{{route('admin-externallinks')}}">{{trans('lang.externallinks')}}  <i class="fa fa-link"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-ambassadors') active @endif" href="{{route('admin-ambassadors')}}">{{trans('lang.ambassadors')}}  <i class="fa fa-link"></i></a>

		<a class="navigate-item btn btn-block bg-gray @if(app('request')->route()->getName()=='admin-contacts') active @endif" href="{{route('admin-contacts')}}">{{trans('lang.contacts')}}  <i class="fa fa-envelope"></i></a>


	</div>

</aside>




{{--  Start Modal  --}}
<div class="modal fade" id="edit_admin_profile_modal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="{{route('admin-edit-admin-profile')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body" style="direction: rtl;text-align: right;">
					<h3>تعديل البيانات</h3>
					<hr>

					<input type="hidden" value="{{Auth::guard('admin')->user()->id}}" name="admin_id">
					<div class="form-group">
						<label for="">{{trans('lang.name')}}:</label>
						<input type="text" class="form-control" value="{{Auth::guard('admin')->user()->name}}" name="name">
					</div>
					<div class="form-group">
						<label for="">{{trans('lang.email')}}:</label>
						<input type="text" class="form-control" name="email" value="{{Auth::guard('admin')->user()->email}}">
					</div>
					<div class="form-froup">
						<label for="phone">{{trans('lang.phone')}}</label>
						<input type="text" min="1" max="10" class="form-control" name="phone" placeholder="{{trans('lang.phone')}}" value="{{Auth::guard('admin')->user()->phone}}">
					</div>
					<div class="form-group">
						<label for="">{{trans('lang.password')}}:</label>
						<input type="password" class="form-control" name="password">
					</div>
					<div class="form-group">
						<label for="">{{trans('lang.image')}}:</label>
						<input type="file" class="form-control" name="image">
					</div>
				</div>
				<div class="modal-footer" style="display:block;">

					<button type="submit" class="btn btn-primary">{{trans('lang.edit')}}</button>

					<button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

				</div>
			</form>
		</div>
	</div>
</div>
{{--  End Modal  --}}