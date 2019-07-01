<?php

/**
 * Translation for locations (RU).
 */

return [
    'list' => [
        'header' => 'Населенные Пункты',
    ],
    'form' => [
        'header' => [
            'create' => 'Создать Новый Населенный Пункт',
            'edit' => 'Редактировать Населенный Пункт'
        ],
        'field' => [
            'parent_id' => 'Родительское месторосположение'
        ]
    ],
    'type' => [
        'region' => 'Область',
        'district' => 'Район',
        'locality' => 'Населенный Пункт'
    ],
    'flash' => [
        'created' => ':Location успешно создана.',
        'updated' => ':Location успешно обновлена.',
        'deleted' => ':Location успешно удалена.',
    ],
    'modal' => [
        'delete' => [
            'header' => 'Удалить :Location',
            'body' => [
                'confirmation' => 'Вы уверены, что хочете удалить :Location <b>(все дочерние населенные пункты будут также удалены)</b>?',
            ]
        ]
    ]
];
