const dropZoneModule = {
  /**
   * Initialize module.
   * @param settings - Settings to override default.
   */
  __init: function (settings) {
    dropZoneModule._config = {
      /**
       * Css selector of the product image dropzone.
       */
      dropzoneSelector: '#productImageUpload',
      getFilesUrl: '/product/{id}/images'
    };

    // Allow overriding the default config
    $.extend( dropZoneModule._config, settings );

    dropZoneModule._setup();
  },

  /**
   * Run application, add listeners
   */
  _setup: function() {
    //Run dropzone for product
    dropZoneModule.uploadProductImages();
  },

  //Dropzone for products
  uploadProductImages: function() {
    $(dropZoneModule._config.dropzoneSelector).dropzone({
      acceptedFiles: 'image/gif,image/jpeg,image/png',
      addRemoveLinks: true,
      url: true,
      autoProcessQueue: false,
      maxFilesize: 10,
      maxFiles: 10,
      init: function() {
        const $id = $('input[name=_id]');
        if ($id.length) {
          const zone = this;
          $.getJSON("/products/images/" + $id.val(), function (files) {
            files.forEach(function(item) {
              zone.files.push(item);
              zone.emit('addedfile', item);
              zone.createThumbnailFromUrl(item,
                zone.options.thumbnailWidth,
                zone.options.thumbnailHeight,
                zone.options.thumbnailMethod,
                true,
                function (dataUrl) {
                  zone.emit("thumbnail", item, dataUrl);
                });
            });
          });
        }

        this.on("addedfile", function(file) {
          setTimeout(function () {
            $(file.previewElement).append('<input type="hidden" name="files[]" value="' + file.dataURL + '">');
            $(dropZoneModule._config.dropzoneSelector).sortable({
              items:'.dz-preview',
              cursor: 'move',
              opacity: 0.5,
              containment: "parent",
              distance: 20,
              tolerance: 'pointer',
            });
          }, 100);
        });
      },
    });
  }
};
