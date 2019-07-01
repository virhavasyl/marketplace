@extends('admin::layouts.master')

@section('content')
  @component('admin::components.datatable', [
    'header' => trans('admin::product.list.header'),
    'formFilterUrl' => LaravelLocalization::getLocalizedURL(null, '/products'),
    'listLimits' => $listLimits,
    'itemsPerPage' => $itemsPerPage,
    'createUrl' => LaravelLocalization::getLocalizedURL(null, '/products/create'),
    'data' => $data,
    'pagination' => $pagination
  ])
    <table class="table table-striped table-bordered datatable dataTable no-footer sortable-table" id="data_table" role="grid" aria-describedby="DataTables_Table_0_info" style="border-collapse: collapse !important">
      <thead>
      <tr role="row">
        <th class="sorting {{ $sortBy == 'id' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="id">@lang('admin::field.id')</th>
        <th class="sorting {{ $sortBy == 'title' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="title">@lang('admin::field.title')</th>
        <th class="sorting {{ $sortBy == 'category' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="category">@lang('admin::field.category_id')</th>
        <th class="sorting {{ $sortBy == 'fullname' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="fullname">@lang('admin::field.fullname')</th>
        <th class="sorting {{ $sortBy == 'price' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="price">@lang('admin::field.price')</th>
        <th class="sorting {{ $sortBy == 'status' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="status">@lang('admin::field.status')</th>
        <th class="sorting {{ $sortBy == 'condition' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="condition">@lang('admin::field.condition')</th>
        <th class="sorting {{ $sortBy == 'updated_at' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="updated_at">@lang('admin::field.updated_at')</th>
        <th class="icons-3-w" aria-controls="data_table">@lang('admin::field.actions')</th>
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
            <select id="category_id" name="category_id" class="select2 form-control w-100 {{ $errors->has('category_id') ? ' is-invalid' : '' }}" data-class="w-300">
              <option value="">@lang('placeholder.all')</option>
              @foreach($categories as $category)
                <option class="l1" value="{{ $category->id }}" {{ @$_GET['category_id'] != null && @$_GET['category_id'] == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                @foreach($category->children as $subcategory)
                  <option class="l2" value="{{ $subcategory->id }}" {{ @$_GET['category_id'] != null && @$_GET['category_id'] == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->title }}</option>
                    @foreach($subcategory->children as $subsubcategory)
                      <option class="l3" value="{{ $subsubcategory->id }}" {{ @$_GET['category_id'] != null && @$_GET['category_id'] == $subsubcategory->id ? 'selected' : '' }}>{{ $subsubcategory->title }}</option>
                  @endforeach
                @endforeach
              @endforeach;
            </select>
        </td>
        <td>
          <input class="form-control" name="fullname" value="{{ @$_GET['fullname'] }}" placeholder="@lang('admin::field.fullname')" />
        </td>
        <td>
          <input class="form-control" name="price" value="{{ @$_GET['price'] }}" placeholder="@lang('admin::field.price')" />
        </td>
        <td>
          <select name="status" class="custom-select form-control">
            <option value="">@lang('placeholder.all')</option>
            @foreach ($statuses as $key => $item)
              <option value="{{ $key }}" {{ @$_GET['status'] != null && @$_GET['status'] == $key ? 'selected' : '' }}> {{ $item }} </option>
            @endforeach
          </select>
        </td>
        <td>
          <select name="condition" class="custom-select form-control">
            <option value="">@lang('placeholder.all')</option>
            @foreach ($conditions as $key => $item)
              <option value="{{ $key }}" {{ @$_GET['condition'] != null && @$_GET['condition'] == $key ? 'selected' : '' }}> {{ $item }} </option>
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
          <td>{{ $item->category->title }}</td>
          <td>{{ $item->user->fullname }}</td>
          <td>{{ $item->price }}</td>
          <td><span class="badge
             @switch($item->status)
                @case(0)
                  badge-secondary
                  @break
                @case(1)
                  badge-success
                  @break
                @default
                  ''
             @endswitch">
          {{ $item->status_text }}</span></td>
          <td>{{ $item->condition_text }}
          <td>{{ $item->updated_at }}</td>
          <td class="icons-3-w">
            <a class="btn btn-info" href="{{ LaravelLocalization::getLocalizedURL(null, "/products/{$item->id}/edit") }}" data-toggle="tooltip" data-placement="top" title="@lang('admin::button.edit')">
              <i class="fa fa-edit"></i>
            </a>
            <span data-toggle="tooltip" data-placement="top" title="@lang('admin::button.delete')">
              <a class="btn btn-danger"
                  href="#confirmationModal"
                  data-toggle="modal"
                  data-url="{{ LaravelLocalization::getLocalizedURL(null, "/products/$item->id") }}"
                  data-modal-header="@lang('admin::product.modal.delete.header', ['title' => $item->title])"
                  data-modal-body="@lang('admin::product.modal.delete.body.confirmation', ['title' => $item->title])">
                  <i class="fa fa-trash-o"></i>
              </a>
            </span>
            @if($item->is_active)
              <span data-toggle="tooltip" data-placement="top" title="@lang('admin::button.block')">
                <a class="btn btn-secondary" 
                    href="#confirmationModal"
                    data-toggle="modal"
                    data-method="PATCH"
                    data-url="{{ LaravelLocalization::getLocalizedURL(null, "/products/{$item->id}/block") }}"
                    data-modal-header="@lang('admin::product.modal.block.header', ['title' => $item->title])"
                    data-modal-body="@lang('admin::product.modal.block.body.confirmation', ['title' => $item->title])">
                  <i class="fa fa-ban"></i>
                </a>
              </span>
            @else
              <span data-toggle="tooltip" data-placement="top" title="@lang('admin::button.activate')">
                <a class="btn btn-secondary" 
                    href="#confirmationModal"
                    data-toggle="modal"
                    data-method="PATCH"
                    data-url="{{ LaravelLocalization::getLocalizedURL(null, "/products/{$item->id}/activate") }}"
                    data-modal-header="@lang('admin::product.modal.activate.header', ['title' => $item->title])"
                    data-modal-body="@lang('admin::product.modal.activate.body.confirmation', ['title' => $item->title])">
                  <i class="fa fa-check-square-o"></i>
                </a>
              </span>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endcomponent

  <!-- Include modal popups for delete confirmation -->
  @include('admin::includes.modals.confirmation')
@stop