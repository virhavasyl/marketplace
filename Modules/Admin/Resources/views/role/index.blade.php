@extends('admin::layouts.master')

@section('content')
  @component('admin::components.datatable', [
    'header' => trans('admin::role.list.header'),
    'formFilterUrl' => LaravelLocalization::getLocalizedURL(null, '/roles'),
    'listLimits' => $listLimits,
    'itemsPerPage' => $itemsPerPage,
    'createUrl' => LaravelLocalization::getLocalizedURL(null, '/roles/create'),
    'data' => $data,
    'pagination' => $pagination
  ])
    <table class="table table-striped table-bordered datatable dataTable no-footer sortable-table" id="data_table">
      <thead>
      <tr role="row">
        <th class="sorting {{ $sortBy == "id" ? "sorting_{$sortDir}" : "" }}" aria-controls="data_table" data-field="id">@lang('admin::field.id')</th>
        <th class="sorting {{ $sortBy == "title" ? "sorting_{$sortDir}" : "" }}" aria-controls="data_table" data-field="title">@lang('admin::field.title')</th>
        <th aria-controls="data_table">@lang('admin::field.actions')</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>
          <input class="form-control" name="id" value="{{ @$_GET['id'] }}" placeholder="@lang('admin::field.id')" />
        </td>
        <td>
          <input class="form-control" name="title" value="{{ @$_GET['title'] }}" placeholder="@lang('admin::field.title')" />
        </td>
        <td>
          <button class="btn btn-block btn-warning w-auto reset-filter" type="button">
            <i class="fa fa-refresh"></i> @lang('admin::button.reset')
          </button>
          <input type="hidden" name="sort_by" value="{{ $sortBy }}" />
          <input type="hidden" name="sort_dir" value="{{ $sortDir }}" />
        </td>
      </tr>
      @foreach ($data as $index => $item)
        <tr role="row" class="{{ $index % 2 === 0 ? 'event' : 'odd' }}">
          <td>{{ $item->id }}</td>
          <td>{{ $item->title }}</td>
          <td>
            <a class="btn btn-info" href="{{ LaravelLocalization::getLocalizedURL(null, "/roles/{$item->id}/edit") }}">
              <i class="fa fa-edit"></i> @lang('admin::button.edit')
            </a>
            @if($item->users_count)
              <a class="btn btn-danger"
                 href="#infoModal"
                 data-toggle="modal"
                 data-modal-header="@lang('admin::role.modal.delete.header', ['role' => $item->title])"
                 data-modal-body="@lang('admin::role.modal.delete.body.info', [
                                  'role' => $item->title,
                                  'delete_url' => LaravelLocalization::getLocalizedURL(null, "/users?role_id=$item->id")
                                 ])">
                <i class="fa fa-trash-o"></i> @lang('admin::button.delete')
              </a>
            @else
              <a class="btn btn-danger"
                 href="#confirmationModal"
                 data-toggle="modal"
                 data-url="{{ LaravelLocalization::getLocalizedURL(null, "/roles/$item->id") }}"
                 data-modal-header="@lang('admin::role.modal.delete.header', ['role' => $item->title])"
                 data-modal-body="@lang('admin::role.modal.delete.body.confirmation', ['role' => $item->title])">
                <i class="fa fa-trash-o"></i> @lang('admin::button.delete')
              </a>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endcomponent

  <!-- Include modal popups for delete confirmation -->
  @include('admin::includes.modals.confirmation')
  @include('admin::includes.modals.info')
@stop