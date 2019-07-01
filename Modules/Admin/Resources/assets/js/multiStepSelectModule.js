const multiStepSelectModule = {
  /**
   * Initialize module.
   * @param settings - Settings to override default.
   */
  __init: function (settings) {
    multiStepSelectModule._config = {
      /**
       * Last level list item (li) selector
       */
      lastLevelListItemSelector: '.multi-step-list ul.level3 li',

      /**
       * List item (li) selector
       */
      listItemSelector: '.multi-step-list li',
      
      /**
       * Select button selector.
       */
      selectSelector: '.multi-step-selector',
    };

    // Temp storage
    multiStepSelectModule._storage = {};

    // Allow overriding the default config
    $.extend( multiStepSelectModule._config, settings );

    multiStepSelectModule._setup();
  },

  /**
   * Run application, add listeners
   */
  _setup: function() {
    const $selectSelector = $(multiStepSelectModule._config.selectSelector);
    if ($selectSelector.length) {
      // Click on select button
      $('body').on('click', multiStepSelectModule._config.selectSelector, multiStepSelectModule.openList)

      // List item hover
      .on('mouseenter', multiStepSelectModule._config.listItemSelector, multiStepSelectModule.itemHover)

      // Select list item
      .on('click',  multiStepSelectModule._config.lastLevelListItemSelector, multiStepSelectModule.selectItem)

      // Click outside the list
      .mouseup(multiStepSelectModule.clickOutside);
    }
  },

  /**
   * Close the list.
   *
   * @private
   */
  _closeList: function () {
    multiStepSelectModule._storage.$list.addClass('d-none');
    multiStepSelectModule._storage.$list.find('ul:not(.level1), ul:not(.level1) li').addClass('d-none');
    multiStepSelectModule._storage.$list.find('li').removeClass('selected');
    multiStepSelectModule._storage = {};
  },

  /**
   * Set selected text into input.
   *
   * @param id - selected ID.
   * @private
   */
  _setText: function (id) {
    const text = multiStepSelectModule._storage.$list.find('li[data-id=' + id + ']').text();
    multiStepSelectModule._storage.$text.val(text);
  },

  /**
   * Show list and select value.
   *
   * @param id - Selected ID.
   * @private
   */
  _showList: function (id) {
    const $selected = multiStepSelectModule._storage.$list.find('li[data-id=' + id + ']');
    if ($selected.length) {
      $selected.addClass('selected');
      $selected.closest('ul').removeClass('d-none');

      const $parent = multiStepSelectModule._storage.$list.find('li[data-id=' + $selected.data('parent-id') + ']');
      if ($parent.length) {
        $parent.addClass('selected');
        $parent.closest('ul').removeClass('d-none');

        const $first = multiStepSelectModule._storage.$list.find('li[data-id=' + $parent.data('parent-id') + ']');
        if ($first.length) {
          $first.addClass('selected');
          $first.closest('ul').removeClass('d-none');

          $parent.closest('ul').find('li[data-parent-id!=' + $first.data('id') + ']').addClass('d-none');
          $parent.closest('ul').find('li[data-parent-id=' + $first.data('id') + ']').removeClass('d-none');

          $selected.closest('ul').find('li[data-parent-id!=' + $parent.data('id') + ']').addClass('d-none');
          $selected.closest('ul').find('li[data-parent-id=' + $parent.data('id') + ']').removeClass('d-none');
        }
      }
    }
  },

  /**
   * Open list.
   *
   * @param event
   */
  openList: function(event) {
    const listId = $(event.target).data('target-list');
    const $list = $('#' + listId);
    const inputId = $(event.target).data('target-input');
    const $input = $('#' + inputId);
    const textId = $(event.target).data('target-text');
    const $text = $('#' + textId);
    multiStepSelectModule._storage.$input = $input;
    multiStepSelectModule._storage.$list = $list;
    multiStepSelectModule._storage.$text = $text;
    if ($list.length) {
      const selected = $input.val();
      if (selected) {
        multiStepSelectModule._showList(selected);
      }
      $list.removeClass('d-none');
    }
  },

  /**
   * Hover list item
   *
   * @param event
   */
  itemHover: function (event) {
    const $target = $(event.target);
    $target.closest('ul').find('li').removeClass('selected');
    $target.addClass('selected');
    const $list = $target.closest('.multi-step-list');
    const id = $target.data('id');
    const $ul = $target.closest('ul');
    if ($ul.hasClass('level1')) {
      $list.find('.level3').addClass('d-none');
      $list.find('.level3 li').removeClass('selected');
      $list.find('.level2').find('li[data-parent-id!=' + id + ']').addClass('d-none').removeClass('selected');
      $list.find('.level2').find('li[data-parent-id=' + id + ']').removeClass('d-none');
      $list.find('.level2').removeClass('d-none');
    } else if($ul.hasClass('level2')) {
      $list.find('.level3').find('li[data-parent-id!=' + id + ']').addClass('d-none').removeClass('selected');
      $list.find('.level3').find('li[data-parent-id=' + id + ']').removeClass('d-none');
      $list.find('.level3').removeClass('d-none');
    }
  },

  /**
   * Select list item
   *
   * @param event
   */
  selectItem: function (event) {
    const $target = $(event.target);
    const id = $target.data('id');
    multiStepSelectModule._storage.$input.val(id).trigger('change');
    multiStepSelectModule._setText(id);
    multiStepSelectModule._closeList();
  },

  /**
   * Click outside the list.
   *
   * @param event
   */
  clickOutside: function (event) {
    if (multiStepSelectModule._storage.$list && !multiStepSelectModule._storage.$list.is(event.target) && multiStepSelectModule._storage.$list.has(event.target).length === 0){
      multiStepSelectModule._closeList();
    }
  }
};
