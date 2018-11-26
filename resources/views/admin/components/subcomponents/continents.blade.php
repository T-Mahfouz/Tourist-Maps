<div class="main-header col-md-12">

  <button class="add-button btn btn-primary" data-toggle='modal' data-target='#add_continent_modal'>{{trans('lang.add_continent')}}</button>


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
        <th scope="col">{{trans('lang.name_en')}}</th>
        <th scope="col">{{trans('lang.icon')}}</th>
        <th scope="col">{{trans('lang.countries_count')}}</th>
        <th scope="col">{{trans('lang.places_count')}}</th>
        <th scope="col">{{trans('lang.options')}}</th>
      </tr>
    </thead>
    <tbody>
     @foreach($continents as $index=>$item)
     <tr>
      <th scope="row">{{$index+1}}</th>
      <td>{{$item->name}}</td>
      <td>{{$item->name_en}}</td>
      <td><img class="profile-pic-small" src="{{$resource.'images/continents/'.$item->icon}}"/></td>
      <td>{{$item->countries_count}}</td>
      <td>{{$item->places_count}}</td>
      <td>
        
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editContinent_{{$item->id}}">{{trans('lang.edit')}}</button>

        <button onclick="deleteContinent({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>
        
      </td>
    </tr>

    {{--  Start Modal  --}}
    <div class="modal fade" id="editContinent_{{$item->id}}" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form action="{{route('admin-edit-continent')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="direction: rtl;text-align: right;">
              <h3>{{$item->name}}</h3>
              <hr>
              
              <input type="hidden" value="{{$item->id}}" name="continent_id">
              <div class="form-group">
                <label for="">{{trans('lang.name')}}:</label>
                <input type="text" class="form-control" value="{{$item->name}}" name="name">
              </div>
              <div class="form-froup">
                <label for="title">{{trans('lang.name_en')}}</label>
                <input type="text" class="form-control" name="name_en" placeholder="{{trans('lang.name_en')}}" value="{{$item->name_en}}">
              </div>
              <div class="form-froup">
                <label for="title">{{trans('lang.icon')}}</label>
                <input type="file" class="form-control" name="image" placeholder="{{trans('lang.name')}}">
              </div>
              <div class="col-md-12 float-right">
              </div>
            </div>
            <div class="modal-footer" style="display:block;">
              <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
              <button type="submit" class="btn btn-default">{{trans('lang.edit')}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{--  End Modal  --}}

    @endforeach
  </tbody>
</table>
{{$continents->links()}}
</div>


<div class="modal" tabindex="-1" role="dialog" id="add_continent_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin-add-continent')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">

          <div class="form-froup">
            <label for="title">{{trans('lang.name')}}</label>
            <input type="text" class="form-control" name="name" placeholder="{{trans('lang.name')}}">
          </div>
          <div class="form-froup">
            <label for="title">{{trans('lang.name_en')}}</label>
            <input type="text" class="form-control" name="name_en" placeholder="{{trans('lang.name_en')}}">
          </div>
          <div class="form-froup">
            <label for="title">{{trans('lang.icon')}}</label>
            <input type="file" class="form-control" name="image" placeholder="{{trans('lang.name')}}">
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">إضافة</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  var delete_url = '{{route("admin-delete-continent")}}';
  var token = '{{Session::token()}}';
</script>