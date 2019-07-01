<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <form action="" method="POST">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-danger min-w-70" data-dismiss="modal">@lang('admin::button.confirmation.no')</button>
          <button type="submit" class="btn btn-primary min-w-70">@lang('admin::button.confirmation.yes')</button>
        </form>
      </div>
    </div>
  </div>
</div>