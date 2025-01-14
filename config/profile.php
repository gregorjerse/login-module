<?php
return [

    // a user should only be able to change login once per year (we may change the duration)
    // but maybe can change it if already changed less than an hour ago
    // set login_change_available = false to allow login change at any moment
    'login_change_available' => [
        'first_interval' => 'PT1H',
        'second_interval' => 'P1Y'
    ],

    'login_validator' => [
        'new' => "/^[a-z0-9-]+$/",
        'existing' => "/^[a-z0-9-_]+$/",
        'length' => [
            'min' => 3,
            'max' => 30
        ]
    ],

    'sections' => [
        'personal' => [
            'login',
            'first_name',
            'last_name',
            'birthday',
            'birthday_year',
            'gender',
            'student_id'
        ],
        'school' => [
            'graduation_year',
            'graduation_grade',
            'nationality',
            'country_code',
            'role'
        ],
        'contact' => [
            'primary_email',
            'secondary_email',
            'address',
            'city',
            'zipcode',
            'primary_phone',
            'secondary_phone',
            'subscription_results',
            'subscription_news'
        ],
        'public' => [
            'public_info',
            'real_name_visible',
            'public_name',
            'picture',
            'presentation',
            'website'
        ],
        'settings' => [
            'language',
            'timezone'
        ]
    ]

];