@if (count($all_attributes))
  <div class="form-group">
    <fieldset>
      <legend>@lang('admin::field.attributes')</legend>
      @foreach($all_attributes as $attribute)
        <div class="form-group">
          @if ($attribute->is_tick_type)
            <div>
              <input id="attribute_{{ $attribute->id }}"
                     type="checkbox"
                     name="attributes[{{ $attribute->id }}]"
                     value="1"
                     {{ old('attributes.' . $attribute->id, isset($selected_attributes[$attribute->id]) ? $selected_attributes[$attribute->id] : null) == 1 ? 'checked' : '' }} />
              <label for="attribute_{{ $attribute->id }}">{{ $attribute->title }}</label>
            </div>
          @else
            <div>
              <label for="attribute_{{ $attribute->id }}">{{ $attribute->title }}</label>
              <select id="attribute_{{ $attribute->id }}" name="attributes[{{ $attribute->id }}]" class="select2 form-control w-100">
                <option value="">@lang('admin::button.select')</option>
                @foreach($attribute->variations as $variation)
                  <option value="{{ $variation->id }}"
                    {{ $variation->id == old('attributes.' . $attribute->id,isset($selected_attributes[$attribute->id]) ? $selected_attributes[$attribute->id] : null) ? 'selected' : '' }}>
                    {{ $variation->title }}
                  </option>
                @endforeach
              </select>
            </div>
          @endif
        </div>
      @endforeach
    </fieldset>
  </div>
@endif