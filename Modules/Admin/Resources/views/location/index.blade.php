@extends('admin::layouts.master')

@section('content')
  @component('admin::components.datatable', [
    'header' => trans('admin::location.list.header'),
    'formFilterUrl' => LaravelLocalization::getLocalizedURL(null, '/locations'),
    'listLimits' => $listLimits,
    'itemsPerPage' => $itemsPerPage,
    'createUrl' => Helper::generateTranslatableUrl('/locations/create', 'parent_id'),
    'data' => $data,
    'pagination' => $pagination
  ])
    <table class="table table-striped table-bordered datatable dataTable no-footer sortable-table" id="data_table">
      <thead>
      <tr role="row">
        <th class="sorting {{ $sortBy == "id" ? "sorting_$sortDir" : "" }}" aria-controls="data_table" data-field="id">@lang('admin::field.id')</th>
        <th class="sorting {{ $sortBy == "title" ? "sorting_$sortDir" : "" }}" aria-controls="data_table" data-field="title">@lang('admin::field.title')</th>
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
          <div class="d-flex align-items-center">
            <label class="switch switch-label switch-primary mr-1 mb-0" data-toggle="tooltip" data-placement="top" title="@lang(@$_GET['global'] ? 'admin::field.global_search.off' : 'admin::field.global_search.on')">
              <input class="switch-input" name="global" type="checkbox" value="1" {{ @$_GET['global'] ? 'checked' : '' }}>
              <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
            <button class="btn btn-block btn-warning w-auto reset-filter" type="button">
              <i class="fa fa-refresh"></i> @lang('admin::button.reset')
            </button>
          </div>

          <input type="hidden" name="parent_id" class="no-reset" value="{{ @$_GET['parent_id'] }}" />
          <input type="hidden" name="sort_by" value="{{ $sortBy }}" />
          <input type="hidden" name="sort_dir" value="{{ $sortDir }}" />
        </td>
      </tr>
      @foreach ($data as $index => $item)
        <tr role="row" class="{{ $index % 2 === 0 ? 'event' : 'odd' }}">
          <td>{{ $item->id }}</td>
          <td>
            @if($item->type != $locality_type)
              <a href="{{ LaravelLocalization::getLocalizedURL(null, "/locations?parent_id={$item->id}") }}">{{ $item->title }}</a>
            @else
              {{ $item->title }}
            @endif
          </td>
          <td>
            <a class="btn btn-info" href="{{ Helper::generateTranslatableUrl("/locations/{$item->id}/edit", "parent_id") }}">
              <i class="fa fa-edit"></i> @lang('admin::button.edit')
            </a>
            <a class="btn btn-danger"
               href="#confirmationModal"
               data-toggle="modal"
               data-url="{{ Helper::generateTranslatableUrl("/locations/{$item->id}", "parent_id") }}"
               data-modal-header="@lang('admin::location.modal.delete.header', ['location' => $item->title])"
               data-modal-body="@lang('admin::location.modal.delete.body.confirmation', ['location' => $item->title])">
              <i class="fa fa-trash-o"></i> @lang('admin::button.delete')
            </a>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endcomponent

  <!-- Include modal popups for delete confirmation -->
  @include('admin::includes.modals.confirmation')
@stop