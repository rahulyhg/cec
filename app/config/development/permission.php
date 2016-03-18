<?php

/**
 * Access Controll List (ACL) Config Variable for Core Framework
 * @var array
 */
define('ROLE_GUEST', 1);
define('ROLE_ADMIN', 5);
define('ROLE_MOD', 10);
define('ROLE_MEMBER', 15);

return [
    ROLE_GUEST => [
        'Core' => [
            'error/*',
            'index/index'
        ],
        'User' => [
            'admin/login',
            'error/*'
        ],
        'Category' => [
            'error/*'
        ],
        'Pcategory' => [
            'error/*'
        ],
        'Article' => [
            'error/*',
            'site/*'
        ],
        'Product' => [
            'error/*'
        ]
    ],

    ROLE_ADMIN => [
        'User' => [
            'error/*',
            'admin/*',
        ],
        'Core' => [
            'error/*',
            'index/*'
        ],
        'Category' => [
            'error/*',
            'admin/*'
        ],
        'Pcategory' => [
            'error/*',
            'admin/*'
        ],
        'Article' => [
            'error/*',
            'admin/*',
            'site/*'
        ],
        'Product' => [
            'error/*',
            'admin/*'
        ],
        'Slug' => [
            'error/*',
            'admin/*',
        ]
    ],

    ROLE_MOD => [
        'User' => [
            'admin/*',
        ],
        'Core' => [
            'error/*',
            'index/index'
        ]
    ],

    ROLE_MEMBER => [
        'User' => [
            'admin/*',
        ],
        'Core' => [
            'error/*',
            'index/index'
        ]
    ]
];
