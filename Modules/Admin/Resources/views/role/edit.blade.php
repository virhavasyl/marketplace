@extends('admin::layouts.master')

@section('content')
  <div class="animated fadeIn">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <form method="POST" action="{{ LaravelLocalization::getLocalizedURL(null, "/roles/{$model->id}") }}">
            @method('PUT')
            @csrf
            <div class="card-header">
              <strong>@lang('admin::role.form.header.edit')</strong>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="title">@lang('admin::field.title')</label>
                    <input class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                           id="title"
                           name="title"
                           type="text"
                           value="{{ old('title', $model->title) }}"
                           autofocus />
                    @if ($errors->has('title'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('title') }}
                      </div>
                    @endif
                  </div>
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