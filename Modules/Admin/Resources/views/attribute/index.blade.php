@extends('admin::layouts.master')

@section('content')
  @component('admin::components.datatable', [
    'header' => trans('admin::attribute.list.header'),
    'formFilterUrl' => LaravelLocalization::getLocalizedURL(null, '/attributes'),
    'listLimits' => $listLimits,
    'itemsPerPage' => $itemsPerPage,
    'createUrl' => LaravelLocalization::getLocalizedURL(null, '/attributes/create'),
    'data' => $data,
    'pagination' => $pagination
  ])
    <table class="table table-striped table-bordered datatable dataTable no-footer sortable-table" id="data_table">
      <thead>
      <tr role="row">
        <th class="sorting {{ $sortBy == "id" ? "sorting_$sortDir" : "" }}" aria-controls="data_table" data-field="id">@lang('admin::field.id')</th>
        <th class="sorting {{ $sortBy == "title" ? "sorting_$sortDir" : "" }}" aria-controls="data_table" data-field="title">@lang('admin::field.title')</th>
        <th class="sorting {{ $sortBy == "type" ? "sorting_$sortDir" : "" }}" aria-controls="data_table" data-field="type">@lang('admin::field.type')</th>
        <th aria-controls="data_table">@lang('admin::field.category_id')</th>
        <th aria-controls="data_table">@lang('admin::field.variation')</th>
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
          <select name="type" class="form-control">
            <option value="">@lang('placeholder.all')</option>
            @foreach ($types as $key => $type)
              <option value="{{ $key }}" {{ @$_GET['type'] == $key ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
          </select>
        </td>
        <td>
          <select name="category_id" class="select2 form-control w-100" data-class="w-300">
            <option value="">@lang('placeholder.all')</option>
            @foreach ($categories as $category)
              <option class="l1" value="{{ $category->id }}" {{ @$_GET['category_id'] == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
              @foreach ($category->children as $subcategory)
                <option class="l2" value="{{ $subcategory->id }}" {{ @$_GET['category_id'] == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->title }}</option>
                @foreach ($subcategory->children as $subsubcategory)
                  <option class="l3" value="{{ $subsubcategory->id }}" {{ @$_GET['category_id'] == $subsubcategory->id ? 'selected' : '' }}>{{ $subsubcategory->title }}</option>
                @endforeach
              @endforeach
            @endforeach
          </select>
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
          <td>{{ $item->title }}</td>
          <td>{{ $item->type_text }}</td>
          <td>
            @foreach($item->categories as $index => $cat)
              @if ($index != 0),@endif
              {{ $cat->title }}
            @endforeach
          </td>
          <td>
            @foreach($item->variations as $index => $variation)
              @if ($index != 0),@endif
              {{ $variation->title }}
            @endforeach
          </td>
          <td>
            <a class="btn btn-info" href="{{ LaravelLocalization::getLocalizedURL(null, "/attributes/{$item->id}/edit") }}">
              <i class="fa fa-edit"></i> @lang('admin::button.edit')
            </a>
            <a class="btn btn-danger"
               href="#confirmationModal"
               data-toggle="modal"
               data-url="{{ LaravelLocalization::getLocalizedURL(null, "/attributes/{$item->id}") }}"
               data-modal-header="@lang('admin::attribute.modal.delete.header', ['attribute' => $item->title])"
               data-modal-body="@lang('admin::attribute.modal.delete.body.confirmation', ['attribute' => $item->title])">
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