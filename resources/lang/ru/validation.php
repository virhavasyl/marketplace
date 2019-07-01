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

    'accepted' => ':attribute должен быть принят.',
    'active_url' => ':attribute не является правильным URL-адресом.',
    'after' => ':attribute должно быть датой, после :date.',
    'after_or_equal' => ':attribute должно быть датой, равной или после :date.',
    'alpha' => ':attribute может содержать только буквы.',
    'alpha_dash' => ':attribute может содержать только буквы, цифры, тире и подчеркивания.',
    'alpha_num' => ':attribute может содержать только буквы и цифры.',
    'array' => ':attribute должен быть массивом.',
    'before' => ':attribute должно быть датой, до :date.',
    'before_or_equal' => ':attribute должно быть датой, равной или до :date.',
    'between' => [
        'numeric' => ':attribute должно быть между :min и :max.',
        'file' => ':attribute должно быть между :min и :max килобайт.',
        'string' => ':attribute должно быть между :min и :max символов.',
        'array' => ':attribute должно быть между :min и :max элементом.',
    ],
    'boolean' => 'Поле :attribute должно быть истинным или ложным.',
    'confirmed' => 'Подтверждение :attribute не совпадает .',
    'date' => ':attribute не является датой.',
    'date_format' => ':attribute не соответствует формату :format.',
    'different' => ':attribute и :other должны быть разными.',
    'digits' => ':attribute должен иметь :digits цифр.',
    'digits_between' => ':attribute должен быть между :min и :max.',
    'dimensions' => ':attribute имеет неверные размеры изображения.',
    'distinct' => 'Поле :attribute имеет повторяющееся значение.',
    'email' => ':attribute должно быть правильным адресом электронной почты.',
    'exists' => 'Выбранный :attribute недействителен.',
    'file' => ':attribute должно быть файлом.',
    'filled' => 'Поле :attribute должно иметь значение.',
    'gt' => [
        'numeric' => ':attribute должно быть больше :value.',
        'file' => ':attribute должно быть больше :value килобайт.',
        'string' => ':attribute должно быть больше :value символов.',
        'array' => ':attribute должно быть больше :value елементов.',
    ],
    'gte' => [
        'numeric' => ':attribute должен быть равным или большим :value.',
        'file' => ':attribute должен быть равным или большим :value килобайт.',
        'string' => ':attribute должен быть равным или большим :value символов.',
        'array' => ':attribute должен быть равным или большим :value елементов.',
    ],
    'image' => ':attribute должно быть изображением.',
    'in' => 'Выбранный :attribute неправильный.',
    'in_array' => 'Поле :attribute не существует в :other.',
    'integer' => ':attribute должен быть целым числом.',
    'ip' => ':attribute должен быть правильным IP-адресом.',
    'ipv4' => ':attribute должен быть правильным IPv4 адресом.',
    'ipv6' => ':attribute должен быть правильным IPv6 адресом.',
    'json' => ':attribute должен быть JSON строкой.',
    'lt' => [
        'numeric' => ':attribute должен быть меньше, чем :value.',
        'file' => ':attribute должен быть меньше, чем :value килобайт.',
        'string' => ':attribute должен быть меньше, чем :value символов.',
        'array' => ':attribute должен быть меньше, чем :value елементов.',
    ],
    'lte' => [
        'numeric' => ':attribute должен быть равным или большим :value.',
        'file' => ':attribute должен быть равным или большим :value килобайт.',
        'string' => ':attribute должен быть равным или большим :value символов.',
        'array' => ':attribute должен быть равным или большим :value елементов.',
    ],
    'max' => [
        'numeric' => ':attribute не может быть больше, чем :max.',
        'file' => ':attribute не может быть больше, чем :max килобайт.',
        'string' => ':attribute не может быть больше, чем :max символов.',
        'array' => ':attribute не может быть больше, чем :max елементов.',
    ],
    'mimes' => ':attribute должен быть файлом типа: :values.',
    'mimetypes' => ':attribute должен быть файлом типа: :values.',
    'min' => [
        'numeric' => ':attribute должен быть меньше :min.',
        'file' => ':attribute должен быть меньше :min килобайт.',
        'string' => ':attribute должен быть меньше :min символов.',
        'array' => ':attribute должен быть меньше :min елементов.',
    ],
    'not_in' => 'Выбранный :attribute неправильный.',
    'not_regex' => 'Формат :attribute неправильный.',
    'numeric' => ':attribute должно быть числом.',
    'present' => 'Поле :attribute должно присутствовать.',
    'regex' => 'Формат :attribute неправильный.',
    'required' => 'Поле :attribute является обязательным.',
    'required_if' => 'Поле :attribute является обязательным, когда :other есть :value.',
    'required_unless' => 'Поле :attribute является обязательным, кроме когда :other имеет :values.',
    'required_with' => 'Поле :attribute является обязательным, когда :values присутствует.',
    'required_with_all' => 'Поле :attribute является обязательным, когда :values присутствует.',
    'required_without' => 'Поле :attribute является обязательным, когда :values не присутствует.',
    'required_without_all' => 'Поле :attribute является обязательным, если не указано ни одного из: :values.',
    'same' => ':attribute и :other должны быть одинаковыми.',
    'size' => [
        'numeric' => ':attribute должно быть :size.',
        'file' => ':attribute должно быть :size килобайт.',
        'string' => ':attribute должно быть :size символов.',
        'array' => ':attribute должно содержать :size елементов.',
    ],
    'string' => ':attribute должен быть строкой.',
    'timezone' => ':attribute должен быть правильной временной зоной.',
    'unique' => ':attribute уже существует.',
    'uploaded' => ':attribute не удалось загрузить.',
    'url' => 'Формат :attribute неправильный.',
    'uuid' => ':attribute должен быть UUID.',

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
            'base64image' => 'Загружать можно только изображения.',
        ],
        'fb_link' => [
            'regex' => 'Поле должно быть правильной facebook ссылкой.'
        ],
        'instagram_link' => [
            'regex' => 'Поле должно быть правильной instagram ссылкой.'
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
