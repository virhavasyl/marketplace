<?php

/**
 * Translation for locations (UK).
 */

return [
    'list' => [
        'header' => 'Населені Пункти',
    ],
    'form' => [
        'header' => [
            'create' => 'Створити Новий Населений Пункт',
            'edit' => 'Редагувати Населений Пункт'
        ],
        'field' => [
            'parent_id' => 'Батьківське місцерозташування'
        ]
    ],
    'type' => [
        'region' => 'Область',
        'district' => 'Район',
        'locality' => 'Населений Пункт'
    ],
    'flash' => [
        'created' => ':Location успішно створено.',
        'updated' => ':Location успішно оновлено.',
        'deleted' => ':Location успішно видалено.',
    ],
    'modal' => [
        'delete' => [
            'header' => 'Видалити :Location',
            'body' => [
                'confirmation' => 'Ви впевнені, що хочете видалити :Location <b>(всі дочірні населені пункти будуть також видалені)</b>?',
            ]
        ]
    ]
];
