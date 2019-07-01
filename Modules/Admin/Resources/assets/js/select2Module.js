const select2Module = {
  /**
   * Initialize module.
   * @param settings - Settings to override default.
   */
  __init: function (settings) {
    select2Module._config = {

      /**
       * jQuery selector of select2.
       */
      select2: 'select.select2',

      /**
       * jQuery selector of select2 for users.
       */
      $select2ForUsersSelector: $('#user'),

      /**
       * Url to search users.
       */
      userSearchUrl: '/users/search',
    };

    // Allow overriding the default config
    $.extend( select2Module._config, settings );

    select2Module._setup();
  },

  /**
   * Run application, add listeners
   */
  _setup: function() {

    //Run Select2
    select2Module.runSelect2();

    //Select2ForUsers
    select2Module._config.$select2ForUsersSelector.select2({
      theme: "bootstrap",
      minimumInputLength: 2,
      ajax: {
        url: select2Module._config.userSearchUrl,
        dataType: 'json',
        data: function (params) {
          return {
            searchTerm: params.term // search term
          };
         },
         processResults: function (response) {
          return {
             results: response
          };
        },
        cache: true
      }
    });
  },

  // Select2
  runSelect2: function() {
    $(select2Module._config.select2).select2({
      theme: "bootstrap",
      templateResult: function (data) {
        // We only really care if there is an element to pull classes from
        if (!data.element) {
          return data.text;
        }

        const $element = $(data.element);
        const $wrapper = $('<div></div>');
        $wrapper.addClass($element[0].className);
        $wrapper.text(data.text);

        return $wrapper;
      }
    }).on('select2:open', function (e) {
      const $dropdown = $('.select2-dropdown');
      const css_class = $(e.target).data('class');
      if ($dropdown.length && css_class) {
        $dropdown.addClass(css_class);
      }
    });
  },
};
