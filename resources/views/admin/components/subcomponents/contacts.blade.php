<div class="main-header col-md-12">
	<!-- <h3>باقات التميز</h3> -->


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
				<th scope="col">{{trans('lang.name')}}</th>
				<th scope="col">{{trans('lang.email')}}</th>
				<th scope="col">{{trans('lang.title')}}</th>
				<th scope="col">{{trans('lang.content')}}</th>
				<th scope="col">{{trans('lang.sendat')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>
			
			@foreach($contacts as $index=>$item)
			
			<tr>
				<th scope="row">{{$index+1}}</th>
				<td>{{$item->name}}</td>
				<td>{{$item->email}}</td>
				<td>{{$item->title}}</td>
				<td><p style="word-wrap: break-word;">{{$item->content}}</p></td>
				<td>{{$item->created_at->diffForHumans()}}</td>
				<td>
					<button style="margin-top: 5px;" onclick="deleteMessage({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>
					
					<button class='btn btn-primary' data-toggle='modal' data-target='#view_messag_{{$item->id}}_modal'>{{trans('lang.view')}}</button>
				</td>
			</tr>

			{{-- VIEW OFFER BANNERS MODAL --}}
			<div class="modal" tabindex="-1" role="dialog" id="view_messag_{{$item->id}}_modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>	
						<div class="modal-body">
							<div style="border-bottom:1px solid #ccc;" class="form-froup">
								<h5 style='color:brown;'>{{trans('lang.name')}}</h5>
								<b>{{$item->name}}</b>	
							</div>

							<div style="border-bottom:1px solid #ccc;" class="form-froup">
								<h5 style='color:brown;'>{{trans('lang.name')}}</h5>
								<b>{{$item->name}}</b>	
							</div>
							<div style="border-bottom:1px solid #ccc;" class="form-froup">
								<h5 style='color:brown;'>{{trans('lang.email')}}</h5>
								<b>{{$item->email}}</b>	
							</div>
							<div style="border-bottom:1px solid #ccc;" class="form-froup">
								<h5 style='color:brown;'>{{trans('lang.title')}}</h5>
								<b>{{$item->title}}</b>	
							</div>
							<div style="border-bottom:1px solid #ccc;" class="form-froup">
								<h5 style='color:brown;'>{{trans('lang.content')}}</h5>
								<b>{{$item->content}}</b>	
							</div>
							<div style="border-bottom:1px solid #ccc;" class="form-froup">
								<h5 style='color:brown;'>{{trans('lang.sendat')}}</h5>
								<b>{{$item->created_at->diffForHumans()}}</b>	
							</div>
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
	{{ $contacts }}
</div>

<script>
	var token = '{{Session::token()}}'
	var delete_message_url = '{{route("admin-delete-contactus")}}'
</script>


