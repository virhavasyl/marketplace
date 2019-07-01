@extends('admin::layouts.master')

@section('content')
  <div class="animated fadeIn">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <form method="POST" action="{{ LaravelLocalization::getLocalizedURL(null, "/currencies/{$model->id}") }}">
            @method('PUT')
            @csrf
            <div class="card-header">
              <strong>@lang('admin::currency.form.header.edit')</strong>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="iso_code">@lang('admin::field.iso_code')</label>
                    <input class="form-control {{ $errors->has('iso_code') ? ' is-invalid' : '' }}"
                           id="iso_code"
                           name="iso_code"
                           type="text"
                           value="{{ old('iso_code', $model->iso_code) }}"
                           autofocus />
                    @if ($errors->has('iso_code'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('iso_code') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="sign">@lang('admin::field.sign')</label>
                    <input class="form-control {{ $errors->has('sign') ? ' is-invalid' : '' }}"
                           id="sign"
                           name="sign"
                           type="text"
                           value="{{ old('sign', $model->sign) }}" />
                    @if ($errors->has('sign'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('sign') }}
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