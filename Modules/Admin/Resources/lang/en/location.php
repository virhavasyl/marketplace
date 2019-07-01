<?php

/**
 * Translation for locations (EN).
 */

return [
    'list' => [
        'header' => 'Locations',
    ],
    'form' => [
        'header' => [
            'create' => 'Create New Location',
            'edit' => 'Edit Location'
        ],
        'field' => [
            'parent_id' => 'Parent location'
        ]
    ],
    'type' => [
        'region' => 'Region',
        'district' => 'District',
        'locality' => 'Locality'
    ],
    'flash' => [
        'created' => ':Location created successfully.',
        'updated' => ':Location updated successfully.',
        'deleted' => ':Location deleted successfully.',
    ],
    'modal' => [
        'delete' => [
            'header' => 'Delete :Location',
            'body' => [
                'confirmation' => 'Are you sure you want to delete :Location <b>(all affiliated locations will also be deleted)</b>?',
            ]
        ]
    ]
];
