<div class="modal" tabindex="-1" role="dialog" id="change_admin_status_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin-change-admin-status')}}" method="post">
        @csrf
        <div class="modal-body">
          <input type="hidden" id="status_admin_id" name="admin_id">
          <p>Are you sure that you want to change status</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">تغيير الحالة</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
        </div>
      </form>
    </div>
  </div>
</div>