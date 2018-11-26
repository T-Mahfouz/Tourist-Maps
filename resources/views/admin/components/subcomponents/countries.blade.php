<div class="main-header col-md-12">

  <form action="">

    <select style="display: inline-block;top: 3px;" class="form-control col-md-3" name="continent" id="">
      <option {{($request->continent != null && $request->continent != 0)?'selected':''}} value="0">{{trans('lang.all')}}</option>
      @foreach($continents as $continent)
      <option {{($request->continent == $continent->id)?'selected':''}} value="{{$continent->id}}">{{$continent->name}}</option>
      @endforeach
    </select>

    <input type="hidden" name="page" value="{{$page}}">
    <input class="form-control col-md-5" style="width: auto;display: inline;" type="text" placeholder="{{trans('lang.search_country')}}" name="key" value="{{Request::get('phone')}}">
    <input class="btn btn-success" type="submit" value="{{trans('lang.search')}}">
  </form>

  <button style="bottom: 70px;" class="add-button btn btn-primary" data-toggle='modal' data-target='#add_country_modal'>{{trans('lang.add_country')}}</button>


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
        <th scope="col">{{trans('lang.image')}}</th>
        <th scope="col">{{trans('lang.continent')}}</th>
        <th scope="col">{{trans('lang.places_count')}}</th>
        <th scope="col">{{trans('lang.description')}}</th>
        <th scope="col">{{trans('lang.order')}}</th>
        <th scope="col">{{trans('lang.options')}}</th>
      </tr>
    </thead>
    <tbody>
     @foreach($countries as $index=>$item)
     <tr>
      <th scope="row">{{$index+1}}</th>
      <td><img class="profile-pic-small" src="{{$resource.'images/flags/'.$item->flag}}"/>{{$item->name}}</td>
      <td><img class="profile-pic-small" src="{{$resource.'images/countries/'.$item->image}}"/></td>
      <td>{{$item->continent->name}}</td>
      <td>{{$item->places_count}}</td>
      <td>{{$item->description}}</td>
      <td>{{$item->order}}</td>
      <td>

        <button style="margin: 5px;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#editCountry_{{$item->id}}">{{trans('lang.edit')}}</button>

        <button style="margin: 5px;" onclick="deleteCountry({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>

      </td>
    </tr>

    {{--  Start Modal  --}}
    <div class="modal fade" id="editCountry_{{$item->id}}" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form action="{{route('admin-edit-country')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="direction: rtl;text-align: right;">
              <h3>{{$item->name}}</h3>
              <hr>
              
              <input type="hidden" value="{{$item->id}}" name="country_id">
              <div class="form-group">
                <label for="">{{trans('lang.name')}}:</label>
                <input type="text" class="form-control" value="{{$item->name}}" name="name">
              </div>
              <div class="form-group">
                <label for="">{{trans('lang.description')}}:</label>
                <textarea type="text" class="form-control" name="description">{{$item->description}}</textarea>
              </div>
              <div class="form-froup">
                <label for="order">{{trans('lang.order')}}</label>
                <input type="number" min="1" max="1000" class="form-control" name="order" placeholder="{{trans('lang.order')}}" value="{{$item->order}}">
              </div>
              <div class="form-group">
                <label for="">{{trans('lang.continent')}}:</label>
                <select name="continent" class="form-control">
                  @foreach($continents as $continent)
                  <option {{($item->continent_id == $continent->id)?'selected':''}} value="{{$continent->id}}">{{$continent->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="">{{trans('lang.flag')}}:</label>
                <input type="file" class="form-control" name="flag">
              </div>
              <div class="form-group">
                <label for="">{{trans('lang.image')}}:</label>
                <input type="file" class="form-control" name="image">
              </div>

              <div class="col-md-12 float-right">
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
{{$countries->links()}}
</div>


<div class="modal" tabindex="-1" role="dialog" id="add_country_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin-add-country')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-froup">
            <label for="name">{{trans('lang.name')}}</label>
            <input type="text" class="form-control" name="name" placeholder="{{trans('lang.name')}}">
          </div>
          <div class="form-group">
            <label for="">{{trans('lang.description')}}:</label>
            <textarea type="text" class="form-control" name="description"></textarea>
          </div>
          <div class="form-froup">
            <label for="order">{{trans('lang.order')}}</label>
            <input type="number" min="1" max="1000" class="form-control" name="order" placeholder="{{trans('lang.order')}}">
          </div>

          <div class="form-group">
            <label for="">{{trans('lang.continent')}}:</label>
            <select name="continent_id" class="form-control">
              @foreach($continents as $continent)
              <option value="{{$continent->id}}">{{$continent->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="">{{trans('lang.flag')}}:</label>
            <input type="file" class="form-control" name="flag">
          </div>
          <div class="form-group">
            <label for="">{{trans('lang.image')}}:</label>
            <input type="file" class="form-control" name="image">
          </div>
          
        </div>
        <div class="modal-footer">
          <button style="margin: 5px;" type="submit" class="btn btn-success">إضافة</button>
          <button style="margin: 5px;" type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  var delete_coutry_url = '{{route("admin-delete-country")}}';
  var token = '{{Session::token()}}';
</script>