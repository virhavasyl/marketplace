@extends('admin::layouts.master')

@section('content')
  <div class="animated fadeIn">
    <div class="row justify-content-center">
      <div class="col-sm-12">
        <div class="card">
          <form method="POST" action="{{ Helper::generateTranslatableUrl('/categories', 'parent_id') }}">
            @csrf
            <div class="card-header">
              <strong>@lang('admin::category.form.header.create')</strong>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="parent_id">@lang('admin::category.form.field.parent_id')</label>
                    <select id="parent_id" name="parent_id" class="select2 form-control {{ $errors->has('parent_id') ? ' is-invalid' : '' }}">
                      <option value="">@lang('placeholder.top_level')</option>
                      @foreach($categories as $category)
                        <option class="l1" value="{{ $category->id }}" {{ old('parent_id', @$_GET['parent_id']) == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                        @foreach($category->children as $subcategory)
                          <option class="l2" value="{{ $subcategory->id }}" {{ old('parent_id', @$_GET['parent_id']) == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->title }} ({{ $category->title }})</option>
                        @endforeach
                      @endforeach;
                    </select>
                    @if ($errors->has('parent_id'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('parent_id') }}
                      </div>
                    @endif
                  </div>
                </div>
                <!-- /.col-sm-4 -->
              </div>
              <div class="row mt-2">
                @foreach($locales as $locale)
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="title_{{ $locale }}">@lang('admin::field.title') <span class="text-uppercase">[{{ $locale }}]</span></label>
                      <input class="form-control {{ $errors->has("title:$locale") ? ' is-invalid' : '' }}"
                             id="title_{{ $locale }}"
                             name="title:{{ $locale }}"
                             type="text"
                             value="{{ old("title:$locale") }}" />
                      @if ($errors->has("title:$locale"))
                        <div class="invalid-feedback" role="alert">
                          {{ $errors->first("title:$locale") }}
                        </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
              <!-- /.row -->
              <div class="row mt-2">
                @foreach($locales as $locale)
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="description_{{ $locale }}">@lang('admin::field.description') <span class="text-uppercase">[{{ $locale }}]</span></label>
                      <textarea class="form-control"
                                id="description_{{ $locale }}"
                                name="description:{{ $locale }}"
                                rows="5">{{ old("description:$locale") }}</textarea>
                      @if ($errors->has("description:$locale"))
                        <div class="invalid-feedback" role="alert">
                          {{ $errors->first("description:$locale") }}
                        </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
              <!-- /.col-sm-4 -->
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