<div id="ui-view">
  <div class="animated fadeIn">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> {{ $header }}
      </div>
      <div class="card-body">
        <form class="form-filter" action="{{ $formFilterUrl }}">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <div class="dataTables_length">
                  <label>
                    @lang('admin::pagination.per_page.show')
                    <select name="items_per_page" class="custom-select custom-select-sm form-control form-control-sm">
                      @foreach ($listLimits as $limit)
                        <option value="{{ $limit }}" {{ $itemsPerPage == $limit ? 'selected' : '' }}>{{ $limit }}</option>
                      @endforeach
                    </select>
                    @lang('admin::pagination.per_page.entries')
                  </label>
                </div>
              </div>
              <!-- /.col-md-6 -->
              <div class="col-sm-12 col-md-6 text-right">
                <a href="{{ $createUrl }}" class="btn btn-block btn-success d-inline-block w-auto">
                  <i class="fa fa-plus-circle"></i> @lang('admin::button.create')
                </a>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                {{ $slot }}
              </div>
            </div>
          </div>
          <!-- /.dataTables_wrapper-->
        </form>
        <div class="row">
          <div class="col-sm-12 col-md-5">
            @lang('admin::pagination.stats', ['start' => $pagination['start'], 'end' => $pagination['end'], 'total' => $pagination['total']])
          </div>
          <div class="col-sm-12 col-md-7">
            {{ $data->links('admin::vendor.pagination.bootstrap') }}
          </div>
        </div>
      </div>
      <!-- /.card-body-->
    </div>
    <!-- /.card-->
  </div>
  <!-- /.animated-->
</div>