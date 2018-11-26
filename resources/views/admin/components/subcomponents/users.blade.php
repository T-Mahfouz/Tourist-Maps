<div class="main-header col-md-12">

  <form action="" method="get">

    <input class="form-control" style="width: auto;display: inline;" type="text" placeholder="{{trans('lang.search_phone_name_email')}}" name="key" value="{{Request::get('phone')}}">
    <input class="btn btn-success" type="submit" value="{{trans('lang.search')}}">
  </form>

  <button style="bottom: 70px;" class="add-button btn btn-primary" data-toggle='modal' data-target='#add_user_modal'>{{trans('lang.add_usr')}}</button>

  <button style="bottom: 70px;right: 15%;" class="add-button btn btn-info bg-green" data-toggle='modal' data-target='#send_notifications_modal'>{{trans('lang.send_notifications')}}</button>

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
        <th scope="col">{{trans('lang.status')}}</th>
        <th scope="col">{{trans('lang.last_seen')}}</th>
        <th scope="col">{{trans('lang.options')}}</th>
      </tr>
    </thead>
    <tbody>
     @foreach($users as $index=>$item)
     <tr>
      <th scope="row">{{$index+1}}</th>
      <td><img class="profile-pic-small" src="{{$resource.'images/users/'.$item->image}}"/>{{$item->fullname}}</td>
      <td>{{$item->email}}</td>
      <td>{{$item->phone}}</td>
      <td><span class="badge badge-{{$item->status?'primary':'dark'}}">{{$item->status?trans('lang.active'):trans('lang.notactive')}}</span></td>
      <td>{{$item->last_seen->diffForHumans()}}</td>
      <td>

        <button data-toggle='modal' data-target='#edit_user_{{$item->id}}_modal' class="btn btn-primary">{{trans('lang.edit')}}</button>

        <button onclick="changeUserStatus({{$item}})" class="btn btn-primary bg-green">
          @if(!$item->status)
          {{trans('lang.changetoactive')}}
          @else
          {{trans('lang.changetonotactive')}}
          @endif
        </button>

        <button data-toggle='modal' data-target='#view_usr_posts_{{$item->id}}_modal' class="btn btn-info">{{trans('lang.view_posts')}}</button>


        <button onclick="deleteUser({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>

      </td>
    </tr>

    {{-- EDIT MODAL --}}
    <div class="modal" tabindex="-1" role="dialog" id="edit_user_{{$item->id}}_modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{route('admin-edit-user')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <div class="form-froup">
                <label for="fullname">{{trans('lang.fullname')}}</label>
                <input type="hidden" class="form-control" name="user_id" value="{{$item->id}}">
                <input type="text" class="form-control" name="fullname" value="{{$item->fullname}}">
              </div>
              <div class="form-froup">
                <label for="username">{{trans('lang.username')}}</label>
                <input type="text" class="form-control" name="username" value="{{$item->username}}">
              </div>
              <div class="form-froup">
                <label for="email">{{trans('lang.email')}}</label>
                <input type="email" class="form-control" name="email" value="{{$item->email}}">
              </div>
              <div class="form-froup">
                <label for="phone">{{trans('lang.phone')}}</label>
                <input type="text" class="form-control" name="phone" value="{{$item->phone}}">
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
              <button type="submit" class="btn btn-success">{{trans('lang.edit')}}</button>&nbsp;
              <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- END EDIT MODAL --}}
    
    {{-- VIEW POSTS MODAL --}}
      <div class="modal" tabindex="-1" role="dialog" id="view_usr_posts_{{$item->id}}_modal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            
            <div class="modal-body">
              @foreach($item->posts as $post)
    
              <div id="user_post_{{$post->id}}_container" style="border-bottom:1px solid #ccc;padding: 10px;margin: 5px;" class="form-froup">
                <b>{{$post->content}}</b>
                <br>
                <i id="{{$post->id}}" onClick='deleteUserPost(this.id)' class="fa fa-trash" style="color: red;cursor: pointer;"></i>
                
              </div>

              @endforeach
            </div>
            <div class="modal-footer">
              
              <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.close')}}</button>
            </div>
          </div>
        </div>
      </div>
      {{-- END VIEW POSTS MODAL --}}

    @endforeach
  </tbody>
</table>
{{$users->links()}}
</div>


{{-- ADD MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="add_user_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin-add-user')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-froup">
            <label for="fullname">{{trans('lang.fullname')}}</label>
            <input type="text" class="form-control" name="fullname">
          </div>
          <div class="form-froup">
            <label for="username">{{trans('lang.username')}}</label>
            <input type="text" class="form-control" name="username">
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


{{-- SEND NOTIFICATIONS MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="send_notifications_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin-send-notifications')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-froup">
            <label for="title">{{trans('lang.title')}}</label>
            <input class="form-control" type="text" name="title">
          </div>
          <div class="form-froup">
            <label for="fullname">{{trans('lang.message')}}</label>
            <textarea type="text" class="form-control" name="message"></textarea>
          </div>
          <br>
          <div class="form-froup">
            <label for="fullname">{{trans('lang.send_all')}}</label>
            <input type="checkbox" name="all">
          </div>
          <div class="form-froup">
            <label for="fullname">{{trans('lang.users')}}</label>
            <select class="form-control" name="users[]" id="" multiple>
              @foreach($allusers as $user)
              <option value="{{$user->id}}">{{$user->fullname}}</option>
              @endforeach
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">{{trans('lang.send')}}</button>&nbsp;
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('lang.cancel')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- END SEND NOTIFICATIONS MODAL --}}


@include('admin.modals.users.delete-user')
@include('admin.modals.users.user-status')

<script>

  function deleteUserPost(id){
    $.ajax({
      method: 'POST',
      url: '{{route("admin-delete-user-post")}}',
      data:{_token:'{{Session::token()}}',post_id:id}
    }).then(function(response){
      $('#user_post_'+id+'_container').css('display','none');
      alert('تم الحذف بنجاح')
    }).catch(function(error){
      alert('حدث خطأ ما يرجى المحاولة مرة أخرى')
      consol(error.response)
    });
    
  }
</script>