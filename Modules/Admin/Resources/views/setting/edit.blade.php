@extends('admin::layouts.master')

@section('content')
  <div class="animated fadeIn">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <form method="POST" action="{{ LaravelLocalization::getLocalizedURL(null, "/settings/{$model->id}") }}">
            @method('PUT')
            @csrf
            <div class="card-header">
              <strong>@lang('admin::setting.form.header.edit')</strong>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="category_id">@lang('admin::field.category_id')</label>
                    <select id="category_id" name="category_id" class="form-control">
                        @foreach ($categories as $key => $item)
                            <option value="{{ $key }}" {{ $key == old('category_id', $model->category_id) ? 'selected' : '' }}> {{ $item }} </option>
                        @endforeach
                    </select>
                    @if ($errors->has('category_id'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('category_id') }}
                    </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="key">@lang('admin::field.key')</label>
                    <input class="form-control {{ $errors->has('key') ? ' is-invalid' : '' }}"
                           id="key"
                           name="key"
                           type="text"
                           value="{{ old('key', $model->key) }}" />
                    @if ($errors->has('key'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('key') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="value">@lang('admin::field.value')</label>
                    <input class="form-control {{ $errors->has('value') ? ' is-invalid' : '' }}"
                           id="value"
                           name="value"
                           type="text"
                           value="{{ old('value', $model->value) }}" />
                    @if ($errors->has('value'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('value') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                </div>
                <!-- /.col-sm-12 -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button class="btn btn-sm btn-success" type="submit">
                <i class="fa fa-save"></i> @lang('admin::button.save')
              </button>
              <button class="btn btn-sm btn-danger reset-button" type="button">
                <i class="fa fa-refresh"></i> @lang('admin::button.reset')
              </button>
            </div>
          </form>
        </div>
        <!-- /.card-->
      </div>
      <!-- /.col-md-6-->
    </div>
    <!-- /.row-->
  </div>
@stop