@extends('admin::layouts.master')

@section('content')
  <div class="animated fadeIn">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <form method="POST" action="{{ LaravelLocalization::getLocalizedURL(null, "/products/{$model->id}") }}">
            @method('PUT')  
            @csrf
            <div class="card-header">
              <strong>@lang('admin::product.form.header.edit')</strong>
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
                  <!-- /.form-group -->
                  <div class="form-group {{ $errors->has('category_id') ? 'is-invalid-group' : '' }}">
                    <label for="category_id">@lang('admin::field.category_id')</label>
                    <div class="input-group">
                      <input id="category_text"
                             class="form-control disabled-normal {{ $errors->has('category_id') ? 'is-invalid' : '' }}"
                             value="{{ old('category_id', $model->category_id) && isset($categories['level3'][old('category_id', $model->category_id)]) ? $categories['level3'][old('category_id', $model->category_id)]['title'] : '' }}"
                             readonly />
                      <div class="input-group-append">
                        <button class="btn btn-secondary multi-step-selector"
                                type="button"
                                data-target-list="catList"
                                data-target-input="category_id"
                                data-target-text="category_text">@lang('admin::button.select')</button>
                      </div>
                    </div>
                    <input id="category_id" name="category_id" type="hidden" value="{{ old('category_id', $model->category_id) }}" />
                    <div id="catList" class="d-none multi-step-list">
                      <ul class="level1">
                        @foreach($categories['level1'] as $catId => $category)
                          <li data-id="{{ $catId }}">{{ $category['title'] }}</li>
                        @endforeach
                      </ul>
                      <ul class="level2 d-none">
                        @foreach($categories['level2'] as $catId => $category)
                          <li data-parent-id="{{ $category['parent_id'] }}" data-id="{{ $catId }}" class="d-none">{{ $category['title'] }}</li>
                        @endforeach
                      </ul>
                      <ul class="level3 d-none">
                        @foreach($categories['level3'] as $catId => $category)
                          <li data-parent-id="{{ $category['parent_id'] }}" data-id="{{ $catId }}" class="d-none">{{ $category['title'] }}</li>
                        @endforeach
                      </ul>
                    </div>
                    @if ($errors->has('category_id'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('category_id') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div id="attributes">
                    @include('admin::category.includes._attributes', [
                      'all_attributes' => $all_attributes,
                      'selected_attributes' => $selected_attributes
                    ])
                  </div>

                  <div class="form-group {{ $errors->has('user_id') ? 'is-invalid-group' : '' }}">
                    <label for="user">@lang('admin::field.fullname')</label>
                    <select id="user" name="user_id" class="form-control w-100 {{ $errors->has('user_id') ? ' is-invalid' : '' }}">
                      @if ($selected_user)
                        <option value="{{ $selected_user->id }}" selected>{{ $selected_user->fullname }}</option>
                      @endif
                    </select>
                    @if ($errors->has('user_id'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('user_id') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="price">@lang('admin::field.price')</label>
                    <div class="row">
                      <div class="col-10">
                        <input class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}"
                               id="price"
                               name="price"
                               type="text"
                               value="{{ old('price', $model->price) }}" />
                        @if ($errors->has('price'))
                          <div class="invalid-feedback" role="alert">
                            {{ $errors->first('price') }}
                          </div>
                        @endif
                      </div>
                      <div class="col-2">
                        <select id="currency" name="currency_id" class="form-control w-100 {{ $errors->has('currency_id') ? ' is-invalid' : '' }}">
                          @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ old('currency_id', $model->currency_id) == $currency->id ? 'selected' : '' }}>{{ $currency->iso_code }}</option>
                          @endforeach;
                        </select>
                        @if ($errors->has('currency_id'))
                          <div class="invalid-feedback" role="alert">
                            {{ $errors->first('currency_id') }}
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="condition">@lang('admin::field.condition')</label>
                    <select id="condition" name="condition" class="form-control {{ $errors->has('condition') ? 'is-invalid' : '' }}">
                      @foreach ($conditions as $key => $item)
                        <option value="{{ $key }}" {{ old('condition', $model->condition) == $key ? 'selected' : '' }}> {{ $item }} </option>
                      @endforeach
                    </select>
                    @if ($errors->has('condition'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('condition') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="description">@lang('admin::field.description')</label>
                    <textarea class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                           id="description"
                           name="description"
                           rows="5" >{{ old('description', $model->description) }}</textarea>
                    @if ($errors->has('description'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('description') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="dZUpload">@lang('admin::field.files')</label>
                    <div id="productImageUpload" class="dropzone">
                      <div class="dz-default dz-message">
                        <i class="fa fa-cloud-upload"></i>
                        <div class="message-text">@lang('admin::field.dZUpload')</div>
                      </div>
                    </div>
                    @if ($errors->has('files'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('files') }}
                      </div>
                    @endif
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <input id="status" type="checkbox" name="status" value="{{ $status_active }}" {{ old('status', $model->status) == $status_active ? 'checked' : ''}} />
                    <label for="status">@lang('admin::product.status.active')</label>
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
            <input type="hidden" name="_id" value="{{ old('_id', $model->id) }}" />
          </form>
        </div>
        <!-- /.card-->
      </div>
      <!-- /.col-md-6-->
    </div>
    <!-- /.row-->
  </div>
@stop