@extends('admin::layouts.master')

@section('content')
  @component('admin::components.datatable', [
    'header' => trans('admin::user.list.header'),
    'formFilterUrl' => LaravelLocalization::getLocalizedURL(null, '/users'),
    'listLimits' => $listLimits,
    'itemsPerPage' => $itemsPerPage,
    'createUrl' => LaravelLocalization::getLocalizedURL(null, '/users/create'),
    'data' => $data,
    'pagination' => $pagination
  ])
    <table class="table table-striped table-bordered datatable dataTable no-footer sortable-table" id="data_table" role="grid" aria-describedby="DataTables_Table_0_info" style="border-collapse: collapse !important">
      <thead>
      <tr role="row">
        <th class="sorting {{ $sortBy == 'id' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="id">@lang('admin::field.id')</th>
        <th class="sorting {{ $sortBy == 'email' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="email">@lang('admin::field.email')</th>
        <th class="sorting {{ $sortBy == 'firstname' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="firstname">@lang('admin::field.firstname')</th>
        <th class="sorting {{ $sortBy == 'lastname' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="lastname">@lang('admin::field.lastname')</th>
        <th class="sorting {{ $sortBy == 'phone' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="phone">@lang('admin::field.phone')</th>
        <th class="sorting {{ $sortBy == 'status' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="status">@lang('admin::field.status')</th>
        <th class="sorting {{ $sortBy == 'role_id' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="role_id">@lang('admin::field.role_id')</th>
        <th class="sorting {{ $sortBy == 'updated_at' ? 'sorting_' . $sortDir : '' }}" aria-controls="data_table" data-field="updated_at">@lang('admin::field.updated_at')</th>
        <th aria-controls="data_table">@lang('admin::field.actions')</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>
          <input class="form-control" name="id" value="{{ @$_GET['id'] }}" placeholder="@lang('admin::field.id')" />
        </td>
        <td>
          <input class="form-control" name="email" value="{{ @$_GET['email'] }}" placeholder="@lang('admin::field.email')" />
        </td>
        <td>
          <input class="form-control" name="firstname" value="{{ @$_GET['firstname'] }}" placeholder="@lang('admin::field.firstname')" />
        </td>
        <td>
          <input class="form-control" name="lastname" value="{{ @$_GET['lastname'] }}" placeholder="@lang('admin::field.lastname')" />
        </td>
        <td>
          <input class="form-control" name="phone" value="{{ @$_GET['phone'] }}" placeholder="@lang('admin::field.phone')" />
        </td>
        <td>
          <select name="status" class="custom-select form-control form-control-sm">
            <option value="">@lang('placeholder.all')</option>
            @foreach ($statuses as $key => $item)
              <option value="{{ $key }}" {{ @$_GET['status'] != null && @$_GET['status'] == $key ? 'selected' : '' }}> {{ $item }} </option>
            @endforeach
          </select>
        </td>
        <td>
          <select name="role_id" class="custom-select form-control form-control-sm">
            <option value="">@lang('placeholder.all')</option>
            @foreach ($roles as $item)
              <option value="{{ $item->id }}" {{ @$_GET['role_id'] == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
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
          <td>{{ $item->email }}</td>
          <td>{{ $item->firstname }}</td>
          <td>{{ $item->lastname }}</td>
          <td>{{ $item->phone }}</td>
          <td><span class="badge
             @switch($item->status)
                @case(0)
                  badge-secondary
                  @break
                @case(1)
                  badge-success
                  @break
                @case(-1)
                  badge-danger
                  @break
                @default
                  ''
             @endswitch">
          {{ $item->status_text }}</span></td>
          <td>{{ $item->role->title }}</td>
          <td>{{ $item->updated_at }}</td>
          <td>
            <a class="btn btn-info" href="{{ LaravelLocalization::getLocalizedURL(null, "/users/{$item->id}/edit") }}" data-toggle="tooltip" data-placement="top" title="@lang('admin::button.edit')">
              <i class="fa fa-edit"></i>
            </a>
            <span data-toggle="tooltip" data-placement="top" title="@lang('admin::button.delete')">
              <a class="btn btn-danger"
                  href="#confirmationModal"
                  data-toggle="modal"
                  data-url="{{ LaravelLocalization::getLocalizedURL(null, "/users/$item->id") }}"
                  data-modal-header="@lang('admin::user.modal.delete.header', ['fullname' => $item->fullname])"
                  data-modal-body="@lang('admin::user.modal.delete.body.confirmation', ['fullname' => $item->fullname])">
                  <i class="fa fa-trash-o"></i>
              </a>
            </span>
            @if($item->is_active)
              <span data-toggle="tooltip" data-placement="top" title="@lang('admin::button.block')">
                <a class="btn btn-warning"
                    href="#confirmationModal"
                    data-toggle="modal"
                    data-method="PATCH"
                    data-url="{{ LaravelLocalization::getLocalizedURL(null, "/users/{$item->id}/block") }}"
                    data-modal-header="@lang('admin::user.modal.block.header', ['fullname' => $item->fullname])"
                    data-modal-body="@lang('admin::user.modal.block.body.confirmation', ['fullname' => $item->fullname])">
                  <i class="fa fa-ban"></i>
                </a>
              </span>
            @else
              <span data-toggle="tooltip" data-placement="top" title="@lang('admin::button.activate')">
                <a class="btn btn-success"
                    href="#confirmationModal"
                    data-toggle="modal"
                    data-method="PATCH"
                    data-url="{{ LaravelLocalization::getLocalizedURL(null, "/users/{$item->id}/activate") }}"
                    data-modal-header="@lang('admin::user.modal.activate.header', ['fullname' => $item->fullname])"
                    data-modal-body="@lang('admin::user.modal.activate.body.confirmation', ['fullname' => $item->fullname])">
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