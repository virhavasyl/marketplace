@extends('admin::layouts.master')

@section('content')
  @component('admin::components.datatable', [
    'header' => trans('admin::setting.list.header'),
    'formFilterUrl' => LaravelLocalization::getLocalizedURL(null, '/settings'),
    'listLimits' => $listLimits,
    'itemsPerPage' => $itemsPerPage,
    'createUrl' => LaravelLocalization::getLocalizedURL(null, '/settings/create'),
    'data' => $data,
    'pagination' => $pagination
  ])
    <table class="table table-striped table-bordered datatable dataTable no-footer sortable-table" id="data_table">
      <thead>
      <tr role="row">
        <th class="sorting {{ $sortBy == "id" ? "sorting_{$sortDir}" : "" }}" aria-controls="data_table" data-field="id">@lang('admin::field.id')</th>
        <th class="sorting {{ $sortBy == "category_id" ? "sorting_{$sortDir}" : "" }}" aria-controls="data_table" data-field="category_id">@lang('admin::field.category_id')</th>
        <th class="sorting {{ $sortBy == "key" ? "sorting_{$sortDir}" : "" }}" aria-controls="data_table" data-field="key">@lang('admin::field.key')</th>
        <th class="sorting {{ $sortBy == "value" ? "sorting_{$sortDir}" : "" }}" aria-controls="data_table" data-field="value">@lang('admin::field.value')</th>
        <th aria-controls="data_table">@lang('admin::field.actions')</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>
          <input class="form-control" name="id" value="{{ @$_GET['id'] }}" placeholder="@lang('admin::field.id')" />
        </td>
        <td>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">@lang('placeholder.all')</option>
                @foreach ($categories as $key => $item)
                    <option value="{{ $key }}" {{ @$_GET['category_id'] != null && @$_GET['category_id'] == $key ? 'selected' : '' }}> {{ $item }} </option>
                @endforeach
            </select>  
        </td>
        <td>
          <input class="form-control" name="key" value="{{ @$_GET['key'] }}" placeholder="@lang('admin::field.key')" />
        </td>
        <td>
          <input class="form-control" name="value" value="{{ @$_GET['value'] }}" placeholder="@lang('admin::field.value')" />
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
          <td>{{ $item->category_text }}</td>
          <td>{{ $item->key }}</td>
          <td>{{ $item->value }}</td>
          <td>
            <a class="btn btn-info" href="{{ LaravelLocalization::getLocalizedURL(null, "/settings/{$item->id}/edit") }}">
              <i class="fa fa-edit"></i> @lang('admin::button.edit')
            </a>
            <a class="btn btn-danger"
                  href="#confirmationModal"
                  data-toggle="modal"
                  data-url="{{ LaravelLocalization::getLocalizedURL(null, "/settings/$item->id") }}"
                  data-modal-header="@lang('admin::setting.modal.delete.header', ['setting' => $item->key])"
                  data-modal-body="@lang('admin::setting.modal.delete.body.confirmation', ['setting' => $item->key])">
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