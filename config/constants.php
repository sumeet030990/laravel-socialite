<?php

return [
    'SERVICE_TYPE' => [
        'facebook',
        'google'
    ],
    'SERVICE_SCOPE' => [
        'facebook' => [
            'scope' => [
                'user_birthday', 'user_location', 'user_age_range', 'user_gender', 'user_hometown' 
            ],
            'fields' => [
                'first_name', 'last_name', 'email', 'gender', 'birthday', 'location', 'hometown', 'age_range',
            ],
        ],
        'google' => [
            'scope' => [
            ],
            'fields' => [
            ],
        ],
    ]
];
