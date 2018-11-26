<div class="main-header col-md-12">

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
				<th scope="col">{{trans('lang.title')}}</th>
				<th scope="col">{{trans('lang.content')}}</th>
				<th scope="col">{{trans('lang.options')}}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{$aboutus->title}}</td>
				<td><p style="word-wrap: break-word;">{{$aboutus->content}}</p></td>

				<td>
					<button onclick="editAboutUs({{$aboutus}})" class="btn btn-primary">{{trans('lang.edit')}}</button>

				</td>
			</tr>
			
		</tbody>
	</table>
</div>



@include('admin.modals.edit-aboutus')


