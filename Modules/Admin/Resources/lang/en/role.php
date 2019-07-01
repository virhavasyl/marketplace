<?php

/**
 * Translation for roles (EN).
 */

return [
    'list' => [
        'header' => 'User Roles',
    ],
    'form' => [
        'header' => [
            'create' => 'Create New User Role',
            'edit' => 'Edit User Role'
        ],
    ],
    'flash' => [
        'created' => 'User role :Role created successfully.',
        'updated' => 'User role :Role updated successfully.',
        'deleted' => 'User role :Role deleted successfully.',
    ],
    'modal' => [
        'delete' => [
            'header' => 'Delete User Role :Role',
            'body' => [
                'confirmation' => 'Are you sure you want to delete the user role :Role?',
                'info' => 'You cannot delete the user role :Role, as it contains users.<br />
                          <a href=\':delete_url\'>Delete</a> all users for this role and try again.'
            ]
        ]
    ]
];
