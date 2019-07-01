@extends('admin::layouts.master')

@section('content')
  <div class="animated fadeIn">
    <div class="row justify-content-center">
      <div class="col-sm-8">
        <div class="card">
          <form method="POST" action="{{ LaravelLocalization::getLocalizedURL(null, "/attributes/{$model->id}") }}">
            @csrf
            @method('PUT')
            <div class="card-header">
              <strong>@lang('admin::attribute.form.header.edit')</strong>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label for="category_id">@lang('admin::field.category_id')</label>
                    <select id="category_id" name="category_id[]" class="select2 form-control w-100 {{ $errors->has('category_id') ? ' is-invalid' : '' }}" multiple="multiple">
                      @foreach($categories as $category)
                        <option class="l1" value="{{ $category->id }}" {{ in_array($category->id, old('category_id', $selectedCategories)) ? 'selected' : '' }}>{{ $category->title }}</option>
                        @foreach($category->children as $subcategory)
                          <option class="l2" value="{{ $subcategory->id }}" {{ in_array($subcategory->id, old('category_id', $selectedCategories)) ? 'selected' : '' }}>{{ $subcategory->title }}</option>
                          @foreach($subcategory->children as $subsubcategory)
                            <option class="l3" value="{{ $subsubcategory->id }}" {{ in_array($subsubcategory->id, old('category_id', $selectedCategories)) ? 'selected' : '' }}>{{ $subsubcategory->title }}</option>
                          @endforeach
                        @endforeach
                      @endforeach;
                    </select>
                    @if ($errors->has('category_id'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('category_id') }}
                      </div>
                    @endif
                  </div>
                </div>
                <!-- /.col-sm-8 -->
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="type">@lang('admin::field.type')</label>
                    <select id="type" name="type" class="form-control attribute-type-select {{ $errors->has('type') ? ' is-invalid' : '' }}">
                      @foreach($types as $key => $type)
                        <option value="{{ $key }}" {{ old('type', $model->type) == $key ? 'selected' : '' }}>{{ $type }}</option>
                      @endforeach;
                    </select>
                    @if ($errors->has('type'))
                      <div class="invalid-feedback" role="alert">
                        {{ $errors->first('type') }}
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
                             value="{{ old("title:$locale", $model->{"title:$locale"}) }}" />
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
              <table class="table table-responsive-sm table-bordered variation-table" {{ old('type', $model->type) != $list_type ? 'style=display:none' : '' }}>
                <thead>
                  <tr>
                    @foreach($locales as $locale)
                      <th>@lang('admin::field.variation') <span class="text-uppercase">[{{ $locale }}]</span></th>
                    @endforeach
                    <th>@lang('admin::field.actions')</th>
                  </tr>
                  <tr>
                    @foreach($locales as $locale)
                      <th class="align-top">
                        <input class="form-control variation-input {{ $errors->has("variations") || $errors->has("variations.0.title:$locale") ? ' is-invalid' : '' }}"
                               id="variation_{{ $locale }}"
                               data-target="title:{{ $locale }}"
                               type="text" />
                        @if ($errors->has("variations"))
                          <div class="invalid-feedback" role="alert">
                            {{ $errors->first("variations") }}
                          </div>
                        @elseif($errors->has("variations.0.title:$locale"))
                          <div class="invalid-feedback" role="alert">
                            {{ $errors->first("variations.0.title:$locale") }}
                          </div>
                        @endif
                      </th>
                    @endforeach
                    <th class="align-top">
                      <button class="btn btn-sm btn-primary submit-variation" type="button" data-toggle="tooltip" data-placement="top" title="@lang('admin::button.add')">
                        <i class="fa fa-plus"></i>
                      </button>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach(old('variations', $selectedVariations) as $key => $variation)
                    <tr>
                      @foreach($locales as $locale)
                        <td>
                          <input class="form-control inline-variation-input" name="variations[{{ $key }}][title:{{ $locale }}]" value="{{ $variation['title:' . $locale] }}" readonly="readonly">
                        </td>
                      @endforeach
                      <td>
                        <button type="button" class="btn btn-sm btn-primary edit-variation" data-toggle="tooltip" data-placement="top" title="@lang('admin::button.edit')">
                          <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-variation" data-toggle="tooltip" data-placement="top" title="@lang('admin::button.delete')">
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
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