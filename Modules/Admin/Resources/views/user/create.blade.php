@extends('admin::layouts.master')

@section('content')
  <div class="animated fadeIn">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <form method="POST" action="{{ LaravelLocalization::getLocalizedURL(null, '/users') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
              <strong>@lang('admin::user.form.header.create')</strong>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="email">@lang('admin::field.email')</label>
                    <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                           id="email"
                           name="email"
                           type="text"
                           value="{{ old('email') }}"
                           autofocus />
                    @if ($errors->has('email'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('email') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="password">@lang('admin::field.password')</label>
                    <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                           id="password"
                           name="password"
                           type="password"
                           value="{{ old('password') }}" />
                    @if ($errors->has('password'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('password') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="firstname">@lang('admin::field.firstname')</label>
                    <input class="form-control {{ $errors->has('firstname') ? ' is-invalid' : '' }}"
                           id="firstname"
                           name="firstname"
                           type="text"
                           value="{{ old('firstname') }}" />
                    @if ($errors->has('firstname'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('firstname') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="lastname">@lang('admin::field.lastname')</label>
                    <input class="form-control {{ $errors->has('lastname') ? ' is-invalid' : '' }}"
                           id="lastname"
                           name="lastname"
                           type="text"
                           value="{{ old('lastname') }}" />
                    @if ($errors->has('lastname'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('lastname') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                </div>
                <!-- /.col-sm-12 -->
              </div>
              <!-- /.row -->
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="avatar">@lang('admin::field.avatar_path')</label>
                    <div class="input-group">
                      <input id="avatar"
                             type="file"
                             name="avatar"
                             class="custom-file-upload"
                             accept="image/png,image/gif,image/jpeg"
                             data-target-input="avatar_path"
                             data-target-preview="preview"
                             data-target-remove="remove_avatar" />
                      <input id="avatar_path" class="form-control disabled-normal" value="{{ old('avatar_filename') }}" readonly />
                      <input id="avatar_path_hidden" type="hidden" name="avatar_filename" value="{{ old('avatar_filename') }}" />
                      <div class="input-group-append">
                        <button class="btn btn-secondary upload-btn" type="button">@lang('admin::button.upload')</button>
                      </div>
                    </div>
                    <button id="remove_avatar"
                            class="btn btn-danger mt-2 float-right {{ old('avatar_realpath') ? '' : 'd-none' }} remove-btn"
                            type="button"
                            data-target-input="avatar_path"
                            data-target-preview="preview"
                            data-target-file="avatar">@lang('admin::button.delete')</button>
                  </div>
                  <!-- /.form-group -->
                </div>
                <!-- /.col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <div id="preview" class="image-preview">
                      @if (old('avatar_realpath'))
                        <img src="{{ old('avatar_realpath') }}" />
                        <input type="hidden" name="avatar_realpath" value="{{ old('avatar_realpath') }}" />
                      @endif
                    </div>
                  </div>
                </div>
                <!-- /.col-sm-6 -->
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="phone">@lang('admin::field.phone')</label>
                    <input class="form-control phone-mask{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                           id="phone"
                           name="phone"
                           type="text"
                           value="{{ old('phone') }}" />
                    @if ($errors->has('phone'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('phone') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="status">@lang('admin::field.status')</label>
                    <select id="status" name="status" class="form-control">
                      @foreach ($statuses as $key => $item)
                        <option value="{{ $key }}" {{ old('status', $active_status) == $key ? 'selected' : '' }}> {{ $item }} </option>
                      @endforeach
                    </select>
                    @if ($errors->has('status'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('status') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="role_id">@lang('admin::field.role_id')</label>
                    <select id="role_id" name="role_id" class="form-control">
                      @foreach ($roles as $item)
                        <option value="{{ $item->id }}" {{ old('role_id') == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('role_id'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('role_id') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="fb_link">@lang('admin::field.fb_link')</label>
                    <input class="form-control {{ $errors->has('fb_link') ? ' is-invalid' : '' }}"
                           id="fb_link"
                           name="fb_link"
                           type="text"
                           value="{{ old('fb_link') }}" />
                    @if ($errors->has('fb_link'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('fb_link') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="instagram_link">@lang('admin::field.instagram_link')</label>
                    <input class="form-control {{ $errors->has('instagram_link') ? ' is-invalid' : '' }}"
                           id="instagram_link"
                           name="instagram_link"
                           type="text"
                           value="{{ old('instagram_link') }}" />
                    @if ($errors->has('instagram_link'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('instagram_link') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="description">@lang('admin::field.description')</label>
                    <textarea class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                              id="description"
                              name="description"
                              rows="5" >{{ old('description') }}</textarea>
                    @if ($errors->has('description'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('description') }}
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