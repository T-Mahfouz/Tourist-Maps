<div class="main-header col-md-12">

  <form action="" method="get">

    <input class="form-control" style="width: auto;display: inline;" type="text" placeholder="{{trans('lang.search_phone_name_email')}}" name="key" value="{{Request::get('phone')}}">
    <input class="btn btn-success" type="submit" value="{{trans('lang.search')}}">
  </form>


  <button data-toggle='modal' data-target='#add_admin_modal' class="add-button btn btn-primary" style="bottom: 70px;">
   إضافة  مدير جديد
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
        <th scope="col">{{trans('lang.phone')}}</th>
        <th scope="col">{{trans('lang.last_seen')}}</th>
        <th scope="col">{{trans('lang.options')}}</th>
      </tr>
    </thead>
    <tbody>
     @foreach($admins as $index=>$item)
     <tr>
      <th scope="row">{{$index+1}}</th>
      <td><img class="profile-pic-small" src="{{$resource.'images/users/'.$item->image}}"/>{{$item->name}}</td>
      <td>{{$item->email}}</td>
      <td>{{$item->phone}}</td>
      <td>{{$item->last_seen->diffForHumans()}}</td>
      <td>

        <button data-toggle='modal' data-target='#edit_admin_{{$item->id}}_modal' class="btn btn-primary">{{trans('lang.edit')}}</button>
        
        @if($item->id != Auth::guard('admin')->user()->id)
        <button onclick="deleteAdmin({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>
        @endif

      </td>
    </tr>
    {{--  Start Modal  --}}
    <div class="modal fade" id="edit_admin_{{$item->id}}_modal" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form action="{{route('admin-edit-admin')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="direction: rtl;text-align: right;">
              <h3>تعديل البيانات</h3>
              <hr>

              <input type="hidden" value="{{$item->id}}" name="admin_id">
              <div class="form-group">
                <label for="">{{trans('lang.name')}}:</label>
                <input type="text" class="form-control" value="{{$item->name}}" name="name">
              </div>
              <div class="form-group">
                <label for="">{{trans('lang.email')}}:</label>
                <input type="text" class="form-control" name="email" value="{{$item->email}}">
              </div>
              <div class="form-froup">
                <label for="phone">{{trans('lang.phone')}}</label>
                <input type="text" min="1" max="10" class="form-control" name="phone" placeholder="{{trans('lang.phone')}}" value="{{$item->phone}}">
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
    @endforeach
  </tbody>
</table>
{{$admins->links()}}
</div>


{{-- ADD MODAL --}}
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
          <div class="form-froup">
            <label for="name">{{trans('lang.name')}}</label>
            <input type="text" class="form-control" name="name">
          </div>
          <div class="form-froup">
            <label for="email">{{trans('lang.email')}}</label>
            <input type="email" class="form-control" name="email">
          </div>
          <div class="form-froup">
            <label for="phone">{{trans('lang.phone')}}</label>
            <input type="text" class="form-control" name="phone">
          </div>
          <div class="form-froup">
            <label for="password">{{trans('lang.password')}}</label>
            <input type="password" class="form-control" name="password">
          </div>
          <div class="form-froup">
            <label for="file">{{trans('lang.image')}}</label>
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



<script>
  var token = '{{Session::token()}}'
  var admin_delete_admin_url = '{{route("admin-delete-admin")}}'
</script>