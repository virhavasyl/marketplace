<?php

/**
 * Translation for roles (UK).
 */

return [
    'list' => [
        'header' => 'Ролі Користувачів',
    ],
    'form' => [
        'header' => [
            'create' => 'Створити Нову Роль Користувача',
            'edit' => 'Редагувати Роль Користувача'
        ],
    ],
    'flash' => [
        'created' => 'Роль користувача :Role успішно створено.',
        'updated' => 'Роль користувача :Role успішно оновлено.',
        'deleted' => 'Роль користувача :Role успішно видалено.'
    ],
    'modal' => [
        'delete' => [
            'header' => 'Видалити Роль Користувача :Role',
            'body' => [
                'confirmation' => 'Ви впевнені, що хочете видалити роль користувача :Role?',
                'info' => 'Ви не можете видалити роль користувача :Role, оскільки вона містить користувачів.<br />
                          <a href=\':delete_url\'>Видаліть</a> спочатку всіх користувачів для цієї ролі та 
                           повторіть спробу.'
            ]
        ]
    ]
];
