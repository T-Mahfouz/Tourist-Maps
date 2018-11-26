<div class="main-header col-md-12">


	<button data-toggle='modal' data-target='#add_offer_modal' class="add-button btn btn-primary">
		إضافة عرض جديد
	</button>


</div>

<div class="main-body col-md-12">

	@if(session()->has('feedback'))
	<div class="alert alert-info">
		{{session()->get('feedback')}}
	</div>
	@endif

	<table class="table table-hover">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">{{trans('lang.title')}}</th>
				<th scope="col">{{trans('lang.content')}}</th>
				<th scope="col">{{trans('lang.link')}}</th>
				<th scope="col">{{trans('lang.image')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($offers as $index => $item)
			<tr>
				<td>{{$index+1}}</td>
				<td>{{$item->title}}</td>
				<td>{{$item->content}}</td>
				<td>{{$item->link}}</td>
				<td><img style="height: 70px;width: 130px;margin: 5px;" src="{{$resource.'images/offers/'.$item->image}}"/></td>

				<td>
					<button data-toggle='modal' data-target='#add_offer_banner_{{$item->id}}_modal' class="btn btn-primary">{{trans('lang.add_banner')}}</button>
					
					<button data-toggle='modal' data-target='#view_offer_banners_{{$item->id}}_modal' class="btn btn-primary">{{trans('lang.view_banners')}}</button>

					<button data-toggle='modal' data-target='#edit_offer_{{$item->id}}_modal' class="btn btn-primary">{{trans('lang.edit')}}</button>

					<button onclick="deleteOffer({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>

				</td>
			</tr>

			{{-- EDIT MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="edit_offer_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{route('admin-edit-offer')}}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<div class="form-froup">
									<label for="title">{{trans('lang.title')}}</label>
									<input type="text" class="form-control" name="title" value="{{$item->title}}">
								</div>
								<div class="form-froup">
									<label for="content">{{trans('lang.content')}}</label>
									<textarea type="text" class="form-control" name="content">{{$item->content}}</textarea>
								</div>
								<div class="form-froup">
									<label for="link">{{trans('lang.link')}}</label>
									<input type="text" class="form-control" name="link" value="{{$item->link}}">
								</div>

								<div class="form-froup">
									<label for="title">{{trans('lang.image')}}</label>
									<input type="hidden" name="offer_id" value="{{$item->id}}">
									<input type="file" class="form-control" name="image">
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-success">{{trans('lang.edit')}}</button>&nbsp;
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			{{-- END EDIT MODAL --}}
			

			{{-- ADD BANNER MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="add_offer_banner_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{route('admin-add-banner')}}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="modal-body">
								<div class="form-froup">
									<label for="description">{{trans('lang.description')}}</label>
									<input type="hidden" value="{{$item->id}}" name="offer_id">
									<textarea type="text" class="form-control" name="description"></textarea>
								</div>
								<div class="form-froup">
									<label for="title">{{trans('lang.image')}}</label>
									<input type="file" class="form-control" name="image">
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-success">{{trans('lang.add')}}</button>&nbsp;
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			{{-- END ADD BANNER MODAL --}}

			{{-- VIEW OFFER BANNERS MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="view_offer_banners_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						
						<div class="modal-body">
							@foreach($item->banners as $banner)
							<div id="offer_banner_{{$banner->id}}" style="border-bottom:1px solid #ccc;" class="form-froup">
								<i id="{{$banner->id}}" onClick='deleteBanner(this.id)' class="fa fa-trash" style="color: red;cursor: pointer;"></i>
								<i data-id='{{$banner->id}}' onClick='editBanner(this,{{$banner}},{{$item->id}})' class="fa fa-edit" style="color: #006ec4;cursor: pointer;"></i>
								<img style="height: 120px;width: 100%;" src="{{$resource.'images/banners/'.$banner->image}}" alt="">
								<br>
								<b>{{$banner->description}}</b>
							</div>

							@endforeach
						</div>
						<div class="modal-footer">
							
							<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.close')}}</button>
						</div>
					</div>
				</div>
			</div>
			{{-- END VIEW OFFER BANNERS MODAL --}}	
			@endforeach
		</tbody>
	</table>
</div>


{{-- ADD MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="add_offer_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-add-offer')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="form-froup">
						<label for="title">{{trans('lang.title')}}</label>
						<input type="text" class="form-control" name="title">
					</div>
					<div class="form-froup">
						<label for="content">{{trans('lang.content')}}</label>
						<textarea type="text" class="form-control" name="content"></textarea>
					</div>
					<div class="form-froup">
						<label for="link">{{trans('lang.link')}}</label>
						<input type="text" class="form-control" name="link">
					</div>

					<div class="form-froup">
						<label for="title">{{trans('lang.image')}}</label>
						<input type="file" class="form-control" name="image">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">{{trans('lang.add')}}</button>&nbsp;
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>
{{-- END ADD MODAL --}}


{{-- EDIT BANNER MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="edit_offer_banner_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('admin-edit-banner')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="form-froup">
						<label for="description">{{trans('lang.description')}}</label>
						<input type="hidden" id="edit_banner_id" name="banner_id">
						<textarea type="text" class="form-control" id="edit_banner_description" name="description"></textarea>
					</div>
					<div class="form-froup">
						<label for="title">{{trans('lang.image')}}</label>
						<input type="file" class="form-control" name="image">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">{{trans('lang.edit')}}</button>&nbsp;
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>
{{-- END EDIT BANNER MODAL --}}

<script>
	var delete_offer_url = '{{route("admin-delete-offer")}}'
	var delete_delete_banner_url = '{{route("admin-delete-banner")}}'
	var token = '{{Session::token()}}'

	function deleteBanner(id){
		$.ajax({
			method: 'POST',
			url: delete_delete_banner_url,
			data:{_token:token,offer_banner_id:id}
		}).then(function(response){
			$('#offer_banner_'+id).css('display','none');
			alert('تم الحذف بنجاح')
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة مرة أخرى')
		});
	}
	function editBanner(el,banner,item){
		let id = el.attributes['data-id'].nodeValue;
		console.log(banner)
		$("#view_offer_banners_"+item+"_modal").modal('hide');

		$("#edit_banner_id").val(banner.id);
		$("#edit_banner_description").val(banner.description);
		$("#edit_offer_banner_modal").modal();
	}

</script>


