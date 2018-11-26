<div class="modal" tabindex="-1" role="dialog" id="edit_admin_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin-edit-admin')}}" method="post">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" id="admin_id" name="admin_id">
            <label for="categories[]">التصنيفات</label>
            <select class="form-control" multiple id="admin_categories" name="categories[]">
              
            </select>
          </div> 
          

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">تعديل</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
        </div>
      </form>
    </div>
  </div>
</div>