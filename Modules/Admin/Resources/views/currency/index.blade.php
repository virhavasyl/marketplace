@extends('admin::layouts.master')

@section('content')
  @component('admin::components.datatable', [
    'header' => trans('admin::currency.list.header'),
    'formFilterUrl' => LaravelLocalization::getLocalizedURL(null, '/currencies'),
    'listLimits' => $listLimits,
    'itemsPerPage' => $itemsPerPage,
    'createUrl' => LaravelLocalization::getLocalizedURL(null, '/currencies/create'),
    'data' => $data,
    'pagination' => $pagination
  ])
    <table class="table table-striped table-bordered datatable dataTable no-footer sortable-table" id="data_table">
      <thead>
      <tr role="row">
        <th class="sorting {{ $sortBy == "id" ? "sorting_{$sortDir}" : "" }}" aria-controls="data_table" data-field="id">@lang('admin::field.id')</th>
        <th class="sorting {{ $sortBy == "iso_code" ? "sorting_{$sortDir}" : "" }}" aria-controls="data_table" data-field="iso_code">@lang('admin::field.iso_code')</th>
        <th aria-controls="data_table" data-field="sign">@lang('admin::field.sign')</th>
        <th aria-controls="data_table">@lang('admin::field.actions')</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>
          <input class="form-control" name="id" value="{{ @$_GET['id'] }}" placeholder="@lang('admin::field.id')" />
        </td>
        <td>
          <input class="form-control" name="iso_code" value="{{ @$_GET['iso_code'] }}" placeholder="@lang('admin::field.iso_code')" />
        </td>
        <td></td>
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
          <td>{{ $item->iso_code }}</td>
          <td>{{ $item->sign }}</td>
          <td>
            <a class="btn btn-info" href="{{ LaravelLocalization::getLocalizedURL(null, "/currencies/{$item->id}/edit") }}">
              <i class="fa fa-edit"></i> @lang('admin::button.edit')
            </a>
            <a class="btn btn-danger"
                  href="#confirmationModal"
                  data-toggle="modal"
                  data-url="{{ LaravelLocalization::getLocalizedURL(null, "/currencies/$item->id") }}"
                  data-modal-header="@lang('admin::currency.modal.delete.header', ['currency' => $item->iso_code])"
                  data-modal-body="@lang('admin::currency.modal.delete.body.confirmation', ['currency' => $item->iso_code])">
                  <i class="fa fa-trash-o"></i> @lang('admin::button.delete')
              </a>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endcomponent

  <!-- Include modal popup for delete confirmation -->
  @include('admin::includes.modals.confirmation')
@stop