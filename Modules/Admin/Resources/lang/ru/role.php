<?php

/**
 * Translation for roles (RU).
 */

return [
    'list' => [
        'header' => 'Роли Пользователей',
    ],
    'form' => [
        'header' => [
            'create' => 'Создать Новую Роль Пользователя',
            'edit' => 'Редактировать Роль Пользователя'
        ],
    ],
    'flash' => [
        'created' => 'Роль пользователя :Role успешно создана.',
        'updated' => 'Роль пользователя :Role успешно обновлена.',
        'deleted' => 'Роль пользователя :Role успешно удалена.'
    ],
    'modal' => [
        'delete' => [
            'header' => 'Удалить Роль Пользователя :Role',
            'body' => [
                'confirmation' => 'Вы уверены, что хотите удалить роль пользователя :Role?',
                'info' => 'Вы не можете удалить роль пользователя :Role, поскольку она содержит пользователей<br />
                          <a href=\':delete_url\'>Удалите</a> сначала всех пользователе для этой роли и
                           повторите попытку.'
            ]
        ]
    ]
];
