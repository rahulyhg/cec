<?php
/**
 * Global configuration.
 */
return [
    'profiler' => true,
    'version' => [
        'css' => 1,
        'js' =>1
    ],
    'baseUrl' => 'cec.dev',
    'staticUrl' => 'cec.dev/public',
    'prefix' => 'cec_',
    'title' => 'CEC - Development',
    'template' => [
        // Controller Scope => Template Name
        'Index' => 'Default',
        'Error' => 'Default',
        'Admin' => 'Default',
        'Site' => 'Default'
    ],
    'mail' => [
        'driver' => 'smtp', // mail, sendmail, smtp
        'host'   => 'smtp.gmail.com',
        'port'   => 465,
        'from'   => [
            'address' => 'contact.saigoncec@gmail.com',
            'name'    => 'SaigonCEC Contact'
        ],
        'encryption' => 'ssl',
        'username'   => 'contact.saigoncec@gmail.com',
        'password'   => 'saigoncec2016',
        'sendmail'   => '/usr/sbin/sendmail -bs',
        // 'viewsDir'   => __DIR__ . '/../app/views/', // optional
    ],
    'defaultLanguage' => 'en', // Default language, which will be choose when region language is not available.
    'cookieEncryptionkey' => 'KkX+DVfEA>196yN',
    'cache' => [
        'lifetime' => 86400,
        'adapter' => 'File',
        'cacheDir' => ROOT_PATH . '/app/var/cache/data/'
    ],
    'logger' => [
        'enabled' => false,
        'path' => ROOT_PATH . '/app/var/logs/',
        'format' => '[%date%][%type%] %message%'
    ],
    'view' => [
        'compiledPath' => ROOT_PATH . '/app/var/cache/volt/',
        'compiledExtension' => '.php',
        'compiledSeparator' => '_',
        'compileAlways' => true
    ],
    'session' => [
        'adapter' => 'Files'
    ],
    'assets' => [
        'local' => 'assets/'
    ],
    'metadata' => [
        'adapter' => 'Memory',
        'metaDataDir' => ROOT_PATH . '/app/var/cache/metadata/'
    ],
    'annotations' => [
        'adapter' => 'Memory',
        'annotationsDir' => ROOT_PATH . '/app/var/cache/annotations/'
    ],
    'user' => [
        'directory' => '/uploads/avatar/',
        'minsize' => 1000,
        'maxsize' => 1000000,
        'mimes' => [
            'image/gif',
            'image/jpeg',
            'image/jpg',
            'image/png',
        ],
        'sanitize' => true
    ],
    'product_article' => [
        'directory' => '/uploads/product_article/',
        'minsize' => 1000,
        'maxsize' => 1000000,
        'mimes' => [
            'image/gif',
            'image/jpeg',
            'image/jpg',
            'image/png',
        ],
        'sanitize' => true,
        'isoverwrite' => false
    ],
    'article_content' => [
        'directory' => '/uploads/article_content/',
        'minsize' => 1000,
        'maxsize' => 1000000,
        'mimes' => [
            'image/gif',
            'image/jpeg',
            'image/jpg',
            'image/png',
        ],
        'sanitize' => true,
        'isoverwrite' => false
    ],
    'homepage' => [
        'directory' => '/uploads/homepage/',
        'minsize' => 1000,
        'maxsize' => 1000000,
        'mimes' => [
            'image/gif',
            'image/jpeg',
            'image/jpg',
            'image/png',
        ],
        'sanitize' => true,
        'isoverwrite' => false
    ],
];
