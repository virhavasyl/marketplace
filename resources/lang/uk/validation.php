<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute повинен бути прийнятий.',
    'active_url' => ':attribute повинен бути правильною URL-адресою.',
    'after' => ':attribute має бути датою, після :date.',
    'after_or_equal' => ':attribute має бути датою, рівною або після :date.',
    'alpha' => ':attribute може містити тільки літери.',
    'alpha_dash' => ':attribute може містити тільки літери, цифри, тире і підкреслення.',
    'alpha_num' => ':attribute може містити тільки літери і цифри.',
    'array' => ':attribute повинен бути масивом.',
    'before' => ':attribute має бути датою до :date.',
    'before_or_equal' => ':attribute має бути датою рівною або до :date.',
    'between' => [
        'numeric' => ':attribute має бути між :min та :max.',
        'file' => ':attribute має бути між :min та :max кілобайт.',
        'string' => ':attribute має бути між :min та :max символів.',
        'array' => ':attribute має бути між :min та :max елементом.',
    ],
    'boolean' => 'Поле :attribute повинно бути істинним або помилковим.',
    'confirmed' => 'Підтвердження :attribute не збігається.',
    'date' => ':attribute не є датою.',
    'date_format' => ':attribute не відповідає формату :format.',
    'different' => ':attribute і :other повинні бути різними.',
    'digits' => ':attribute має мати :digits цифр.',
    'digits_between' => ':attribute має бути між :min та :max .',
    'dimensions' => ':attribute має невірні розміри зображення.',
    'distinct' => 'Поле :attribute має повторюване значення.',
    'email' => ':attribute має бути правильною адресою електронної пошти.',
    'exists' => 'Вибраний :attribute недійсний.',
    'file' => ':attribute має бути файлом.',
    'filled' => 'Поле :attribute має мати значення.',
    'gt' => [
        'numeric' => ':attribute має бути більше :value.',
        'file' => ':attribute має бути більше :value кілобайт.',
        'string' => ':attribute має біти більшим, ніж :value символів.',
        'array' => ':attribute має бути більшим, ніж :value елементів.',
    ],
    'gte' => [
        'numeric' => ':attribute повинен бути рівним або більшим :value.',
        'file' => ':attribute повинен бути рівним або більшим :value кілобайт.',
        'string' => ':attribute повинен бути рівним або більшим :value символів.',
        'array' => ':attribute повинен мати :value елементів або більше.',
    ],
    'image' => ':attribute має бути зображенням.',
    'in' => 'Вибраний :attribute неправильний.',
    'in_array' => 'Поле :attribute не існує в :other.',
    'integer' => ':attribute повинен бути цілим числом.',
    'ip' => ':attribute повинен бути правильною IP-адресою.',
    'ipv4' => ':attribute повинен бути правильною IPv4 адресою.',
    'ipv6' => ':attribute повинен бути правильною IPv6 адресою.',
    'json' => ':attribute повинен бути JSON рядком.',
    'lt' => [
        'numeric' => ':attribute повинен бути меншим, ніж :value.',
        'file' => ':attribute повинен бути меншим, ніж :value кілобайт.',
        'string' => ':attribute повинен бути меншим, ніж :value символів.',
        'array' => ':attribute повинен бути меншим, ніж :value елементів.',
    ],
    'lte' => [
        'numeric' => ':attribute повинен бути рівним або більшим :value.',
        'file' => ':attribute повинен бути рівним або більшим :value кілобайт.',
        'string' => ':attribute повинен бути рівним або більшим :value символів.',
        'array' => ':attribute повинен бути рівним або більшим :value елементів.',
    ],
    'max' => [
        'numeric' => ':attribute не може бути більшим, ніж :max.',
        'file' => ':attribute не може бути більшим, ніж :max кілобайт.',
        'string' => ':attribute не може бути більшим, ніж :max символів.',
        'array' => ':attribute не може бути більшим, ніж :max елементів.',
    ],
    'mimes' => ':attribute повинен бути файлом типу: :values.',
    'mimetypes' => ':attribute повинен бути файлом типу: :values.',
    'min' => [
        'numeric' => ':attribute має бути меншим :min.',
        'file' => ':attribute має бути меншим :min кілобайт.',
        'string' => ':attribute має бути меншим :min символів.',
        'array' => ':attribute має мати менше :min елементів.',
    ],
    'not_in' => 'Вибраний :attribute неправильний.',
    'not_regex' => 'Формат :attribute неправильний.',
    'numeric' => ':attribute має бути числом.',
    'present' => 'Поле :attribute повинно бути присутнім.',
    'regex' => 'Формат :attribute неправильний.',
    'required' => 'Поле :attribute є обов\'язковим.',
    'required_if' => 'Поле :attribute  є обов\'язковим, коли :other є :value.',
    'required_unless' => 'Поле :attribute  є обов\'язковим, окрім коли :other має :values.',
    'required_with' => 'Поле :attribute є обов\'язковим, коли :values присутні.',
    'required_with_all' => 'Поле :attribute є обов\'язковим, коли :values присутні.',
    'required_without' => 'Поле :attribute є обов\'язковим, коли :values не присутні.',
    'required_without_all' => 'Поле :attribute є обов\'язковим, якщо не вказано жодного з: :values.',
    'same' => ':attribute і :other повинні бути однаковими.',
    'size' => [
        'numeric' => ':attribute повинно бути :size.',
        'file' => ':attribute повинно бути :size кілобайт.',
        'string' => ':attribute повинно бути :size символів.',
        'array' => ':attribute повинно містити :size елементів.',
    ],
    'string' => ':attribute повинен бути рядком.',
    'timezone' => ':attribute повинен бути правильною часовою зоною.',
    'unique' => ':attribute вже існує.',
    'uploaded' => ':attribute не вдалося завантажити.',
    'url' => 'Формат :attribute неправильний.',
    'uuid' => ':attribute повинен бути UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'files' => [
            'base64image' => 'Завантажувати можна тільки зображення.',
        ],
        'fb_link' => [
            'regex' => 'Поле має бути правильним facebook посиланням.'
        ],
        'instagram_link' => [
            'regex' => 'Поле має бути правильним instagram посиланням.'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
