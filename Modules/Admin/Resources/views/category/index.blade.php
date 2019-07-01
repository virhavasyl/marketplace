@extends('admin::layouts.master')

@section('content')
  @component('admin::components.datatable', [
    'header' => trans('admin::category.list.header'),
    'formFilterUrl' => LaravelLocalization::getLocalizedURL(null, '/categories'),
    'listLimits' => $listLimits,
    'itemsPerPage' => $itemsPerPage,
    'createUrl' => Helper::generateTranslatableUrl('/categories/create', 'parent_id'),
    'data' => $data,
    'pagination' => $pagination
  ])
    <table class="table table-striped table-bordered datatable dataTable no-footer sortable-table" id="data_table">
      <thead>
      <tr role="row">
        <th class="sorting {{ $sortBy == "id" ? "sorting_$sortDir" : "" }}" aria-controls="data_table" data-field="id">@lang('admin::field.id')</th>
        <th class="sorting {{ $sortBy == "title" ? "sorting_$sortDir" : "" }}" aria-controls="data_table" data-field="title">@lang('admin::field.title')</th>
        <th class="sorting {{ $sortBy == "description" ? "sorting_$sortDir" : "" }}" aria-controls="data_table" data-field="description">@lang('admin::field.description')</th>
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
          <input class="form-control" name="title" value="{{ @$_GET['description'] }}" placeholder="@lang('admin::field.description')" />
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
            <a href="{{ LaravelLocalization::getLocalizedURL(null, "/categories?parent_id={$item->id}") }}">{{ $item->title }}</a>
          </td>
          <td>{{ $item->description }}</td>
          <td>
            <a class="btn btn-info" href="{{ Helper::generateTranslatableUrl("/categories/{$item->id}/edit", "parent_id") }}">
              <i class="fa fa-edit"></i> @lang('admin::button.edit')
            </a>
            @if($item->getProductCount())
              <a class="btn btn-danger"
                 href="#infoModal"
                 data-toggle="modal"
                 data-modal-header="@lang('admin::category.modal.delete.header', ['category' => $item->title])"
                 data-modal-body="@lang('admin::category.modal.delete.body.info', [
                  'category' => $item->title,
                  'delete_url' => LaravelLocalization::getLocalizedURL(null, "/products?category_id=$item->id")
                 ])">
                <i class="fa fa-trash-o"></i> @lang('admin::button.delete')
              </a>
            @else
              <a class="btn btn-danger"
                 href="#confirmationModal"
                 data-toggle="modal"
                 data-url="{{ LaravelLocalization::getLocalizedURL(null, "/categories/$item->id") }}"
                 data-modal-header="@lang('admin::category.modal.delete.header', ['category' => $item->title])"
                 data-modal-body="@lang('admin::category.modal.delete.body.confirmation', ['category' => $item->title])">
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