<div class="modal" tabindex="-1" role="dialog" id="change_user_status_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin-change-user-status')}}" method="post">
        @csrf
        <div class="modal-body">
          <input type="hidden" id="status_user_id" name="user_id">
          <p>هل تريد تغيير حالة المستخدم؟</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">تغيير الحالة</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
        </div>
      </form>
    </div>
  </div>
</div>