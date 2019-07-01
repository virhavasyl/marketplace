const commonModule = {
  /**
   * Initialize module.
   * @param settings - Settings to override default.
   */
  __init: function (settings) {
    commonModule._config = {
      /**
       * Timeout when flash messages will be hidden automatically (milliseconds).
       */
      flashTimeout: 3000,

      /**
       * Selector of tooltip.
       */
      tooltipSelector: '[data-toggle="tooltip"]',

      /**
       * jQuery selector of flash message alert box.
       */
      $messageAlert: $('.flash-message-container .alert'),

      /**
       * jQuery selector of phone mask.
       */
      $phoneMaskSelector: $('.phone-mask'),

      /**
       * jQuery selector of category input.
       */
      categoryInputSelector: '#category_id',

      /**
       * jQuery selector of product attributes.
       */
      $selectorAttribute: $('#attributes'),
    };

    // Allow overriding the default config
    $.extend( commonModule._config, settings );

    commonModule._setup();
  },

  /**
   * Run application, add listeners
   */
  _setup: function() {
    // Tooltips
    $('body').tooltip({
      selector: commonModule._config.tooltipSelector
    });

    //Phone mask
    commonModule._config.$phoneMaskSelector.mask("+38(999) 999-9999");

    // Flash messages
    setTimeout(function() {
      commonModule._config.$messageAlert.alert('close');
    }, commonModule._config.flashTimeout);

    // Modals
    $('.modal').on('show.bs.modal', function (e) {
      if ($(e.relatedTarget).data('modal-header')) {
        $(e.target).find('.modal-header .modal-title').html($(e.relatedTarget).data('modal-header'));
      }
      if ($(e.relatedTarget).data('modal-body')) {
        $(e.target).find('.modal-body').html($(e.relatedTarget).data('modal-body'));
      }
      if ($(e.relatedTarget).data('url')) {
        $(e.target).find('form').attr('action', $(e.relatedTarget).data('url'));
      }
      if ($(e.relatedTarget).data('method')) {
        $(e.target).find("[name='_method']").val($(e.relatedTarget).data('method'));
      }
    });

    //Get attributes for category
    $('body').on('change', commonModule._config.categoryInputSelector, function () {
      const category_id = $(this).val();
      commonModule._config.$selectorAttribute.empty();
      $.get(`/categories/${category_id}/attributes`, function( data ) {
        commonModule._config.$selectorAttribute.html(data);
        setTimeout(select2Module.runSelect2, 1);
      });
    });
  },

  /**
   * Translate message.
   *
   * @param key
   * @param replace
   * @returns {*}
   */
  trans: function (key, replace = {}) {
    const key_parts = key.split('::');
    if (key_parts.length === 1) {
      key = key_parts[0];
      translations = window.global_translations;
    } else {
      key = key_parts[1];
      translations = window.admin_translations;
    }
    let translation = key.split('.').reduce((t, i) => t[i] || null, translations);

    for (let placeholder in replace) {
      translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }

    return translation;
  },
};
