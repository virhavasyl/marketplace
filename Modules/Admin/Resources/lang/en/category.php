<?php

/**
 * Translation for categories (EN).
 */

return [
    'list' => [
        'header' => 'Categories',
    ],
    'form' => [
        'header' => [
            'create' => 'Create New Category',
            'edit' => 'Edit Category'
        ],
        'field' => [
            'parent_id' => 'Parent Category'
        ]
    ],
    'flash' => [
        'created' => 'Category :Category created successfully.',
        'updated' => 'Category :Category updated successfully.',
        'deleted' => 'Category :Category deleted successfully.',
    ],
    'modal' => [
        'delete' => [
            'header' => 'Delete :Category',
            'body' => [
                'confirmation' => 'Are you sure you want to delete category :Category <b>(all subcategories will also be deleted)</b>?',
                'info' => 'You cannot delete the category :Category, as it (or subcategories) contains products.<br />
                          <a href=\':delete_url\'>Delete</a> all products for this category (and all subcategories) and try again.'
            ]
        ]
    ]
];
