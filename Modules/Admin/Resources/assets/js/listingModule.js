const listingModule = {
  /**
   * Initialize module.
   * @param settings - Settings to override default.
   */
  __init: function (settings) {
    listingModule._config = {
      /**
       * Selector of form filter input box on the listing page.
       */
      filterInput: '.form-filter input',

      /**
       * Selector of form filter input/select box in the listing page.
       */
      filterInputSelect: '.form-filter select, .form-filter input',

      /**
       * Selector to skip fields from reset filter form.
       */
      filterNoReset: '.no-reset',

      /**
       * Selector of form filter reset button.
       */
      filterResetButton: '.form-filter .reset-filter',

      /**
       * Selector of sortable table header.
       */
      sortableHeader: '.sortable-table th.sorting',

      /**
       * Selector of sortable table.
       */
      sortableTable: '.sortable-table',

      /**
       * jQuery selector of form filter on the listing page.
       */
      $filterFormSelector: $('.form-filter'),
    };

    // Allow overriding the default config
    $.extend( listingModule._config, settings );

    listingModule._setup();
  },

  /**
   * Run application, add listeners
   */
  _setup: function() {
    /**
     * Even listeners.
     */
    // Click on sortable table header.
    $('body').on('click', listingModule._config.sortableHeader, listingModule.sortData)

    // Change filter input or select.
    .on('change', listingModule._config.filterInputSelect, listingModule.submitClosestForm)

    // Press key on filter input.
    .on('keypress', listingModule._config.filterInput, listingModule.filterKeyPress)

    // Click on filter reset button.
    .on('click', listingModule._config.filterResetButton, listingModule.filterReset)
  },

  /**
   * KeyPress listener of the filter form fields.
   * @param event
   */
  filterKeyPress: function (event) {
    if(event.which === 13) {
      listingModule.submitClosestForm.call(this);
    }
  },

  /**
   * Reset filter form on the listing page.
   */
  filterReset: function () {
    $(this).closest(listingModule._config.sortableTable).find('input:not(' + listingModule._config.filterNoReset + '), select:not(' + listingModule._config.filterNoReset + ')').val('');
    listingModule.submitClosestForm.call(this);
  },

  /**
   * Sort data table.
   */
  sortData: function () {
    listingModule._config.$filterFormSelector.find('[name=sort_by]').val($(this).data('field'));
    if ($(this).hasClass('sorting_asc')) {
      listingModule._config.$filterFormSelector.find('[name=sort_dir]').val('desc');
    } else {
      listingModule._config.$filterFormSelector.find('[name=sort_dir]').val('asc');
    }
    listingModule.submitClosestForm.call(this);
  },

  /**
   * Submit the closest form
   */
  submitClosestForm: function () {
    $(this).closest('form').submit();
  },
};