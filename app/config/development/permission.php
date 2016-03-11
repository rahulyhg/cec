<?php

/**
 * Access Controll List (ACL) Config Variable for Core Framework
 * @var array
 */
define('ROLE_GUEST', 1);
define('ROLE_ADMIN', 5);

return [
    ROLE_GUEST => [
        'Core' => [
            'error/*',
            'index/index'
        ],
        'User' => [
            'site/login',
            'error/*'
        ],
        'Category' => [
            'error/*'
        ]
    ],

    ROLE_ADMIN => [
        'User' => [
            'admin/*',
        ],
        'Core' => [
            'error/*'
        ]
    ]
];
