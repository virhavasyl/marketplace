@extends('admin::layouts.master')

@section('content')
  <div class="animated fadeIn">
    <div class="row justify-content-center">
      <div class="col-sm-8">
        <div class="card">
          <form method="POST" action="{{ Helper::generateTranslatableUrl('/locations', 'parent_id') }}">
            @csrf
            <div class="card-header">
              <strong>@lang('admin::location.form.header.create')</strong>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="parent_id">@lang('admin::location.form.field.parent_id')</label>
                    <select id="parent_id" name="parent_id" class="select2 form-control {{ $errors->has('parent_id') ? ' is-invalid' : '' }}" data-target="type">
                      <option value="" data-type="1">@lang('placeholder.top_level')</option>
                      @foreach($locations as $region)
                        <option class="l1" value="{{ $region->id }}" data-type="2" {{ old('parent_id', @$_GET['parent_id']) == $region->id ? 'selected' : '' }}>{{ $region->title }}</option>
                        @foreach($region->children as $district)
                          <option class="l2" value="{{ $district->id }}" data-type="3" {{ old('parent_id', @$_GET['parent_id']) == $district->id ? 'selected' : '' }}>{{ $district->title }} ({{ $region->title }})</option>
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
                <!-- /.col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="type">@lang('admin::field.type')</label>
                    <select id="type" class="form-control type" disabled>
                      @foreach($types as $key => $value)
                        <option value="{{ $key }}" {{ old('type', $selectedType) == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                    <input class="type" type="hidden" name="type" value="{{ old('type', $selectedType) }}" />
                    @if ($errors->has('type'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('type') }}
                      </div>
                    @endif
                  </div>
                </div>
                <!-- /.col-sm-6 -->
              </div>
              <!-- /.row -->
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