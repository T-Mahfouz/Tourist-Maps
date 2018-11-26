<div class="main-header col-md-12">

  <form action="" method="get">

    <select name="status" id="">
      <option {{($request->status != 1 && $request->status != 0)?'selected':''}} value="2" >{{trans('lang.all')}}</option>
      <option {{($request->status == 1)?'selected':''}} value="1">{{trans('lang.active')}}</option>
      <option {{($request->status == 0)?'selected':''}} value="0">{{trans('lang.notactive')}}</option>
    </select>

    <select style="display: inline-block;top: 3px;" class="" name="country" id="">
      <option {{($request->country != null && $request->country != 0)?'selected':''}} value="0">{{trans('lang.all')}}</option>
      @foreach($countries as $country)
      <option {{($request->country == $country->id)?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
      @endforeach
    </select>


    <select name="type" id="">
      <option value="0">{{trans('lang.all')}}</option>
      @foreach($placesTypes as $type)
      <option  {{($request->type == $type->id)?'selected':''}} value="{{$type->id}}">{{$type->name}}</option>
      @endforeach
    </select>
    <input class="form-control" style="width: auto;display: inline;" type="text" placeholder="{{trans('lang.search_place')}}" name="key" value="{{Request::get('phone')}}">
    <input class="btn btn-success" type="submit" value="{{trans('lang.search')}}">
  </form>

  <button style="bottom: 70px;" class="add-button btn btn-primary" data-toggle='modal' data-target='#add_place_modal'>{{trans('lang.add_place')}}</button>

</div>

<div class="main-body col-md-12">

  @if(session()->has('notActive'))
  <div class="alert alert-info">
    {{session()->get('notActive')}}
  </div>
  @endif
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
        <th scope="col">{{trans('lang.continent')}}</th>
        <th scope="col">{{trans('lang.country')}}</th>
        <th scope="col">{{trans('lang.placetype')}}</th>
        <th scope="col">{{trans('lang.status')}}</th>
        <th scope="col">{{trans('lang.description')}}</th>
        <th scope="col">{{trans('lang.options')}}</th>
      </tr>
    </thead>
    <tbody>
     @foreach($places as $index=>$item)
     <tr>
      <th scope="row">{{$index+1}}</th>
      <td>{{$item->name}}</td>
      <td>{{$item->continent->name}}</td>
      <td>{{$item->country->name}}</td>
      <td>{{$item->place_type}}</td>
      <td><span class="badge badge-{{$item->status?'primary':'dark'}}">{{$item->status?trans('lang.active'):trans('lang.notactive')}}</span></td>
      <td>{{mb_substr($item->description,0,25,"utf-8").'...'}}<td>



        <button onclick="viewPlace({{$item}})" class="btn btn-info">{{trans('lang.view')}}</button>

        <button class="btn btn-primary" data-toggle='modal' data-target='#edit_place_{{$item->id}}_modal'>{{trans('lang.edit')}}</button>

        <button onclick="changePlaceStatus({{$item->id}})" class="btn btn-primary">
          @if(!$item->status)
          {{trans('lang.changetoactive')}}
          @else
          {{trans('lang.changetonotactive')}}
          @endif
        </button>

        <button onclick="deletePlace({{$item->id}})" class="btn btn-danger">{{trans('lang.delete')}}</button>

      </td>
    </tr>

    {{-- EDIT MODAL --}}
    <div class="modal" tabindex="-1" role="dialog" id="edit_place_{{$item->id}}_modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{route('admin-edit-place')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" id="edit_place_modal_body">

              <div class="form-froup">
                <label for="name">{{trans('lang.name')}}</label>
                <input type="hidden" name="place_id" value="{{$item->id}}">
                <input type="text" class="form-control" name="name" value="{{$item->name}}">
              </div>
              <div class="form-froup">
                <label for="address">{{trans('lang.address')}}</label>
                <input type="text" class="form-control" name="address" value="{{$item->address}}">
              </div>
              <div class="form-froup">
                <label for="description">{{trans('lang.description')}}</label>
                <textarea type="text" class="form-control" name="description">{{$item->description}}</textarea>
              </div>

              <div class="form-froup">
                <label for="country">{{trans('lang.country')}}</label>
                <select class="form-control" name="country_id">
                  @foreach($countries as $country)
                  <option {{ ($item->country_id == $country->id)?'selected':'' }} value="{{$country->id}}">{{$country->name}}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-froup">
                <label for="country">{{trans('lang.placetype')}}</label>
                <select onChange="editDisplayLink()" class="form-control" name="place_type_id" id="edit_place_type_id">
                  @foreach($placesTypes as $pt)
                  <option {{ ($item->place_type_id == $pt->id)?'selected':'' }} value="{{$pt->id}}">{{$pt->name}}</option>
                  @endforeach
                </select>
              </div>

              <div  id="edit_booking_link" class="form-froup">
                <label for="country">{{trans('lang.booking_link')}}</label>
                <input class="form-control" type="text" name="booking_link" value="{{$item->booking_link}}">
              </div>

              <div  id="edit_offer_link" class="form-froup">
                <label for="country">{{trans('lang.offer_link')}}</label>
                <input class="form-control" type="text" name="offer_link" value="{{$item->offer_link}}">
              </div>


              <div class="form-froup">
                <label for="file">{{trans('lang.geolocation')}}</label>
                <br>
                <input type="txt" class="col-md-5" name="lon" value="{{$item->lon}}" placeholder="{{trans('lang.lon')}}">
                <input type="txt" class="col-md-5" name="lat" value="{{$item->lat}}" placeholder="{{trans('lang.lat')}}">

              </div>
              <div class="form-group">
                <label for=""><h5 style="color:brown;">{{trans('lang.images')}}</h5 style="color:brown;"></label>

                <div id="">
                  @foreach($item->images as $si)
                  <img onClick="deleteThisImage(this,'{{$si}}',{{$item->id}})" id="image_{{$item->id.'_'.mb_substr($si, 0, 6)}}" style="cursor:pointer;width: 10%;height: 75px;margin: 5px;" src="{{$resource.'images/places/'.$si}}"/>
                  @endforeach
                </div>

              </div>
              <div class="form-froup">
                <label for="file">{{trans('lang.images')}}</label>
                <input type="file" class="form-control" name="images[]" multiple="multiple">
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

    @endforeach
  </tbody>
</table>
{{$places->links()}}
</div>

<script>
  var token = '{{Session::token()}}'
  var delete_place_url = '{{route("admin-delete-place")}}'
  var change_place_status_url = "{{route('admin-change-place-status')}}"
  var delete_place_image = "{{route('admin-delete-place-image')}}"
  var placeImagePath = "{{$resource.'images/places/'}}"
</script>


{{-- ADD MODAL --}}
<div class="modal" tabindex="-1" role="dialog" id="add_place_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin-add-place')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body" id="add_place_modal_body">

          <div class="form-froup">
            <label for="name">{{trans('lang.name')}}</label>
            <input type="text" class="form-control" name="name" value="{{old('name')}}">
          </div>
          <div class="form-froup">
            <label for="address">{{trans('lang.address')}}</label>
            <input type="text" class="form-control" name="address" value="{{old('address')}}">
          </div>
          <div class="form-froup">
            <label for="description">{{trans('lang.description')}}</label>
            <textarea type="text" class="form-control" name="description">{{old('description')}}</textarea>
          </div>

          <div class="form-froup">
            <label for="country">{{trans('lang.country')}}</label>
            <select class="form-control" name="country_id">
              @foreach($countries as $country)
              <option value="{{$country->id}}">{{$country->name}}</option>
              @endforeach
            </select>
          </div>

          <div class="form-froup">
            <label for="country">{{trans('lang.placetype')}}</label>
            <select onchange="displayLink()" class="form-control" name="place_type_id" id="place_type_id">
              @foreach($placesTypes as $pt)
              <option value="{{$pt->id}}">{{$pt->name}}</option>
              @endforeach
            </select>
          </div>

          <div style="display: none;" id="booking_link" class="form-froup">
            <label for="country">{{trans('lang.booking_link')}}</label>
            <input class="form-control" type="text" name="booking_link" value="{{old('booking_link')}}">
          </div>

          <div style="display: none;" id="offer_link" class="form-froup">
            <label for="country">{{trans('lang.offer_link')}}</label>
            <input class="form-control" type="text" name="offer_link" value="{{old('offer_link')}}">
          </div>

          <div class="form-froup">
            <label for="file">{{trans('lang.geolocation')}}</label>
            <br>
            <input type="txt" class="col-md-5" name="lon" placeholder="{{trans('lang.lon')}}" value="{{old('lon')}}">
            <input type="txt" class="col-md-5" name="lat" placeholder="{{trans('lang.lat')}}" value="{{old('lat')}}">

          </div>

          <div class="form-froup">
            <label for="file">{{trans('lang.images')}}</label>&nbsp;<i onclick="addAnotherImage()" class="fa fa-plus" style="color:green;cursor: pointer;"></i>
            <input type="file" class="form-control" name="images[]">
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


@include('admin.modals.places.view-place')



<script>
  function displayLink(){
    value = $('#place_type_id').val();
    if(value == 4){
      $('#booking_link').css('display','block');
      $('#offer_link').css('display','none');
    }
    else if(value == 5){
      $('#offer_link').css('display','block');
      $('#booking_link').css('display','none');
    }
    else{
      $('#offer_link').css('display','none');
      $('#booking_link').css('display','none');
    }
  }
  function editDisplayLink(){
    value = $('#edit_place_type_id').val();
    if(value == 4){
      $('#edit_booking_link').css('display','block');
      $('#edit_offer_link').css('display','none');
    }
    else if(value == 5){
      $('#edit_offer_link').css('display','block');
      $('#edit_booking_link').css('display','none');
    }
    else{
      $('#edit_booking_link').css('display','none');
      $('#edit_offer_link').css('display','none');
    }
  }

  function addAnotherImage(){
    addImage = "<div class='form-froup'><label for='file'>{{trans('lang.images')}}</label>&nbsp;<i onclick='addAnotherImage()' class='fa fa-plus' style='color:green;cursor: pointer;'></i><input type='file' class='form-control' name='images[]'></div>";

    $('#add_place_modal_body').append(addImage);
  }
  function addAnotherImageAtEdit(){
    addAnotherImage = "<div class='form-froup'><label for='file'>{{trans('lang.images')}}</label>&nbsp;<i onclick='addAnotherImageAtEdit()' class='fa fa-plus' style='color:green;cursor: pointer;'></i><input type='file' class='form-control' name='images[]'></div>";


    $('#edit_place_modal_body').append(addAnotherImage);
  }
  function deleteThisImage(item,image,place_id){
     $.ajax({
      method: 'POST',
      url: delete_place_image,
      data: { _token: token, place_id: place_id, image: image}
     }).then( response => {
        $('#image_'+item.id).css('display','none')
        alert('تم الحذف')
     }).catch(error => {
        console.log(error)
        alert('حدث خطأ ما أثناء الحذف');
     });
  }

</script>
