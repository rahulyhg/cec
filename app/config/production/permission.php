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
        ],
        'Company' => [
            'error/*',
            'admin/*',
        ],
        'Homepage' => [
            'error/*',
            'admin/*',
        ],
    ],

    ROLE_MOD => [
        'Core' => [
            'error/*',
            'index/index',
            'index/dashboard'
        ],
        'User' => [
            'admin/login',
            'error/*'
        ],
        'Category' => [
            'error/*',
            'admin/index',
            'admin/create',
            'admin/edit'
        ],
        'Pcategory' => [
            'error/*',
            'admin/index',
            'admin/create',
            'admin/edit'
        ],
        'Article' => [
            'error/*',
            'site/*',
            'admin/index',
            'admin/create',
            'admin/edit',
            'admin/uploadimage',
            'admin/deleteimage'
        ],
        'Product' => [
            'error/*',
            'admin/index',
            'admin/create',
            'admin/edit',
            'admin/uploadimage',
            'admin/deleteimage'
        ]
    ],

    ROLE_MEMBER => [
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
    ]
];
