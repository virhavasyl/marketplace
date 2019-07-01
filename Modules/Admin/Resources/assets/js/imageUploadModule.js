const imageUploadModule = {
  /**
   * Initialize module.
   * @param settings - Settings to override default.
   */
  __init: function (settings) {
    imageUploadModule._config = {
      /**
       * Upload button selector
       */
      uploadButtonSelector: '.custom-file-upload',
      removeButtonSelector: '.remove-btn',
      removeFileInputSelector: '#remove_file_input'
    };

    // Allow overriding the default config
    $.extend( imageUploadModule._config, settings );

    imageUploadModule._setup();
  },

  /**
   * Run application, add listeners
   */
  _setup: function() {
    $('body').on('change', imageUploadModule._config.uploadbuttonSelector, imageUploadModule.upload)
    .on('click', imageUploadModule._config.removeButtonSelector, imageUploadModule.remove);
  },

  /**
   * Upload file (show preview and set file name).
   *
   * @param event
   */
  upload: function (event) {
    if (event.target.files && event.target.files[0]) {
      const reader = new FileReader();
      reader.readAsDataURL(event.target.files[0]);

      reader.onload = function(e) {
        const $input = $(event.target);
        $('#' + $input.data('target-input')).val(event.target.files[0].name);
        $('#' + $input.data('target-preview')).html('<img src="' + e.target.result + '" />');
        $('#' + $input.data('target-remove')).removeClass('d-none');

        const $removeFile = $(imageUploadModule._config.removeFileInputSelector);
        if ($removeFile.length) {
          $removeFile.val('');
        }
      }
    }
  },

  /**
   * Remove uploaded file (remove preview and input value)
   * @param event
   */
  remove: function (event) {
    const $button = $(event.target);
    const file = $button.data('target-file');
    $('#' + file).val('');
    $('#' + $button.data('target-input')).val('');
    $('#' + $button.data('target-input') + '_hidden').remove();
    $('#' + $button.data('target-preview')).empty();
    $button.addClass('d-none');

    const $removeFile = $(imageUploadModule._config.removeFileInputSelector);
    if ($removeFile.length) {
      $removeFile.val(file);
    }
  }
};
