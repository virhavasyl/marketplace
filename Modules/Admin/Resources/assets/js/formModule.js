const formModule = {
  /**
   * Initialize module.
   * @param settings - Settings to override default.
   */
  __init: function (settings) {
    formModule._config = {
      /**
       * Selector of attribute variations type.
       */
      attributeTypeSelectSelector: '.attribute-type-select',

      /**
       * Selector of add attribute variation.
       */
      attributeVariationAddButton: '.submit-variation',

      /**
       * Css class of edit attribute variation input box in the create/edit attribute form
       */
      attributeVariationEditInputClass: 'inline-variation-input',

      /**
       * Selector of attribute variation input box in the create/edit attribute form
       */
      attributeVariationInput: '.variation-input',

      /**
       * Prefix of input name of the attribute variation
       */
      attributeVariationInputNamePrefix: 'new_',

      /**
       * Css class of new row of the attribute variation.
       */
      attributeVariationNewRowClass: 'new',

      /**
       * Css class of cancel attribute variation button in the create/edit attribute form
       */
      cancelVariationButtonClass: 'cancel-variation',

      /**
       * Css class of delete attribute variation button in the create/edit attribute form
       */
      deleteVariationButtonClass: 'delete-variation',

      /**
       * Css class of edit attribute variation button in the create/edit atttribute form
       */
      editVariationButtonClass: 'edit-variation',

      /**
       * Selector of create/edit form reset button.
       */
      formResetButton: '.reset-button',

      /**
       * Css class of save attribute variation button in the create/edit attribute form
       */
      saveVariationButtonClass: 'save-variation',

      /**
       * Css class of failed validation fields.
       */
      validationInvalidClass: 'is-invalid',

      /**
       * Selector of validation message.
       */
      validationMessage: '.invalid-feedback',

      /**
       * Select box with data-target attribute
       */
      targetSelect: 'select[data-target]',

      /**
       * jQuery selector of attribute variations table.
       */
      $attributeVariationTableSelector: $('.variation-table'),
    };

    // Allow overriding the default config
    $.extend( formModule._config, settings );

    formModule._setup();
  },

  /**
   * Clear validation errors in the row.
   *
   * @param $selector - Parent row
   * @private
   */
  _clearValidationErrors: function ($selector) {
    $selector.find('input').removeClass(formModule._config.validationInvalidClass);
    $selector.find(formModule._config.validationMessage).remove();
  },

  /**
   * Close attribute validation editing form.
   *
   * @param $selector
   * @param save
   * @private
   */
  _closeAttrVariationEdit: function ($selector, save = false) {
    $selector.closest('tr').find('input').each(function () {
      $(this).attr('readonly', 'readonly');
      if (!save) {
        $(this).val(localStorage.getItem($(this).attr('name')));
      }
      localStorage.removeItem($(this).attr('name'));
    });
    $selector.closest('tr').find(commonModule._config.tooltipSelector).tooltip('hide');

    formModule._replaceButton($selector.closest('tr').find(`.${formModule._config.cancelVariationButtonClass}`), {
      'class': `btn btn-sm btn-danger ${formModule._config.deleteVariationButtonClass}`,
      'data-toggle': 'tooltip',
      'data-placement': 'top',
      'data-original-title': commonModule.trans('admin::button.delete')
    }, 'fa fa-trash');

    formModule._replaceButton($selector.closest('tr').find(`.${formModule._config.saveVariationButtonClass}`), {
      'class': `btn btn-sm btn-primary ${formModule._config.editVariationButtonClass}`,
      'data-toggle': 'tooltip',
      'data-placement': 'top',
      'data-original-title': commonModule.trans('admin::button.edit')
    }, 'fa fa-edit');
  },

  /**
   * Generate button with attributes.
   *
   * @param attributes
   * @param icon
   * @returns {string}
   */
  _generateButton: function (attributes = {}, icon = null) {
    let button = '<button type="button"';
    Object.keys(attributes).forEach(function(key) {
      button += ` ${key}="${attributes[key]}"`;
    });
    button += '>';
    if (icon) {
      button += `<i class="${icon}"></i>`;
    }
    button += '</button>';

    return button;
  },

  /**
   * Generate input box.
   *
   * @param attributes
   * @returns {string}
   */
  _generateInput: function(attributes = {}) {
    let input = '<input';
    Object.keys(attributes).forEach(function(key) {
      input += ` ${key}="${attributes[key]}"`;
    });
    input += ' />';

    return input;
  },

  /**
   * Replace button.
   *
   * @param $selector
   * @param attributes
   * @param icon
   * @returns {boolean}
   */
  _replaceButton: function ($selector, attributes = {}, icon = null) {
    if (!$selector) {
      return false;
    }

    if (icon) {
      $selector.find('i').remove();
      $selector.prepend(`<i class="${icon}"></i>`);
    }

    Object.keys(attributes).forEach(function(key) {
      $selector.attr(key, attributes[key]);
    });
  },

  /**
   * Run application, add listeners
   */
  _setup: function() {
    /**
     * Even listeners.
     */
    // Click on form reset button.
    $('body').on('click', formModule._config.formResetButton, formModule.formReset)

    // Change target select in the form
    .on('change', formModule._config.targetSelect, formModule.setTargetValue)

    // Press key in the add attribute variation input
    .on('keypress', formModule._config.attributeVariationInput, formModule.attributeVariationKeypress)

    // Click on the add attribute variation button
    .on('click', formModule._config.attributeVariationAddButton, formModule.addAttrVariation)

    // Click on the delete attribute variation button
    .on('click', `.${formModule._config.deleteVariationButtonClass}`, formModule.deleteAttrVariation)

    // Click on the edit attribute variation button
    .on('click', `.${formModule._config.editVariationButtonClass}`, formModule.editAttrVariation)

    // Click on the cancel attribute variation button
    .on('click', `.${formModule._config.cancelVariationButtonClass}`, formModule.cancelAttrVariation)

    // Click on the save attribute variation button
    .on('click', `.${formModule._config.saveVariationButtonClass}`, formModule.saveAttrVariation)

    // Press key in the edit attribute variation input
    .on('keypress', `.${formModule._config.attributeVariationEditInputClass}`, formModule.editAttributeVariationKeypress)

    // Change target select in the form
    .on('change', formModule._config.attributeTypeSelectSelector, formModule.changeAttributeType)
  },

  /**
   * Show validation message.
   *
   * @param $field
   * @param messageKey
   */
  _showValidationMessage: function ($field, messageKey) {
    const fieldName = $field.attr('name') ? $field.attr('name') : $field.data('target');
    const translatedMessage = commonModule.trans(messageKey, {':field': fieldName});
    $field.addClass(formModule._config.validationInvalidClass).after('<div class="invalid-feedback" role="alert">' + translatedMessage + '</div>');
  },

  /**
   * Validate attribute variations.
   *
   * @param $inputs
   * @returns {boolean}
   * @private
   */
  _validateAttributeVariations: function ($inputs) {
    let correct = true;
    $inputs.each(function () {
      if (!$(this).val()) {
        correct = false;
        formModule._showValidationMessage($(this), 'validation.required');
      }
    });

    return correct;
  },

  /**
   * Add attribute variation.
   */
  addAttrVariation: function () {
    formModule._clearValidationErrors($(formModule._config.attributeVariationInput).closest('tr'));
    const $inputs = $(formModule._config.attributeVariationInput);
    if (!formModule._validateAttributeVariations($inputs)) {
      return false;
    }

    let template = `<tr class="${formModule._config.attributeVariationNewRowClass}">`;
    const index = formModule._config.$attributeVariationTableSelector.find(`tbody .${formModule._config.attributeVariationNewRowClass}`).length;
    $inputs.each(function () {
      let input = formModule._generateInput({
        'class': `form-control ${formModule._config.attributeVariationEditInputClass}`,
        'name': 'variations[' + formModule._config.attributeVariationInputNamePrefix + index + '][' + $(this).data('target') + ']',
        'value': $(this).val(),
        'readonly': 'readonly'
      });
      template += '<td>' + input + '</td>';
    });

    const editButton = formModule._generateButton({
      'class': `btn btn-sm btn-primary ${formModule._config.editVariationButtonClass}`,
      'data-toggle': 'tooltip',
      'data-placement': 'top',
      'title': commonModule.trans('admin::button.edit')
    }, 'fa fa-edit');

    const deleteButton = formModule._generateButton({
      'class': `btn btn-sm btn-danger ${formModule._config.deleteVariationButtonClass}`,
      'data-toggle': 'tooltip',
      'data-placement': 'top',
      'title': commonModule.trans('admin::button.delete')
    }, 'fa fa-trash');

    template += `<td>${editButton} ${deleteButton}</td></tr>`;
    formModule._config.$attributeVariationTableSelector.find('tbody').append(template);
    $inputs.val('');
  },

  /**
   * KeyPress listener of attribute variation field.
   * @param event
   * @returns {boolean}
   */
  attributeVariationKeypress: function (event) {
    if(event.which === 13) {
      event.preventDefault();
      formModule.addAttrVariation.call(this);
      return false;
    }
  },

  /**
   * Cancel attribute variation editing.
   */
  cancelAttrVariation: function () {
    formModule._clearValidationErrors($(this).closest('tr'));
    formModule._closeAttrVariationEdit($(this));
  },

  changeAttributeType: function () {
    if ($(this).val() === '2') {
      formModule._config.$attributeVariationTableSelector.show();
    } else {
      formModule._config.$attributeVariationTableSelector.hide();
    }
  },

  /**
   * Delete attribute variation.
   */
  deleteAttrVariation: function () {
    $(this).closest('tr').find(commonModule._config.tooltipSelector).tooltip('hide');
    $(this).closest('tr').remove();
  },

  /**
   * Edit attribute variation.
   */
  editAttrVariation: function () {
    $(this).closest('tr').find('input').each(function (index) {
      $(this).removeAttr('readonly');
      localStorage.setItem($(this).attr('name'), $(this).val());
      if (index === 0) {
        $(this).focus();
      }
    });
    $(this).closest('tr').find(commonModule._config.tooltipSelector).tooltip('hide');

    formModule._replaceButton($(this), {
      'class': `btn btn-sm btn-primary ${formModule._config.saveVariationButtonClass}`,
      'data-toggle': 'tooltip',
      'data-placement': 'top',
      'data-original-title': commonModule.trans('admin::button.save')
    }, 'fa fa-check');

    formModule._replaceButton($(this).closest('tr').find(`.${formModule._config.deleteVariationButtonClass}`), {
      'class': `btn btn-sm btn-danger ${formModule._config.cancelVariationButtonClass}`,
      'data-toggle': 'tooltip',
      'data-placement': 'top',
      'data-original-title': commonModule.trans('admin::button.cancel')
    }, 'fa fa-close');
  },

  /**
   * KeyPress listener of edit attribute variation field.
   * @param event
   * @returns {boolean}
   */
  editAttributeVariationKeypress: function (event) {
    if(event.which === 13) {
      event.preventDefault();
      formModule.saveAttrVariation.call(this);
      return false;
    }
  },

  /**
   * Reset create/edit form.
   */
  formReset: function () {
    $(this).closest('form').find('input:not([type=hidden]), select').val('').removeClass(formModule._config.validationInvalidClass);
    $(this).closest('form').find(formModule._config.validationMessage).remove();

    const $imageRemoveBtn = $(imageUploadModule._config.removeButtonSelector);
    if ($imageRemoveBtn.length) {
      $imageRemoveBtn.click();
    }
  },

  /**
   * Save updated attribute variation.
   */
  saveAttrVariation: function () {
    const $tr = $(this).closest('tr');
    formModule._clearValidationErrors($tr);
    if (formModule._validateAttributeVariations($tr.find('input'))) {
      formModule._closeAttrVariationEdit($(this), true);
    }
  },

  /**
   * Set value of the linked (target) field.
   */
  setTargetValue: function () {
    const target = $(this).data('target');
    $('.' + target).val($(this).find(':selected').data('type'));
  }
};