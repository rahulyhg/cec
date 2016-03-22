<?php
/**
* WARNING
*
* Manual changes to this file may cause a malfunction of the system.
* Be careful when changing settings!
*
*/

return array (
  'db' => 
  array (
    'mysql' => 
    array (
      'adapter' => 'Mysql',
      'host' => 'localhost',
      'port' => '3306',
      'username' => 'root',
      'password' => 'uCGoiZzJd7L94TWWVbau7nMnt',
      'dbname' => 'cec',
      'persistent' => true,
    ),
  ),
  'global' => 
  array (
    'profiler' => false,
    'version' => 
    array (
      'css' => 1,
      'js' => 1,
    ),
    'baseUrl' => 'cec.dev',
    'staticUrl' => 'cec.dev/public',
    'prefix' => 'cec',
    'title' => 'SaigonCEC',
    'template' => 
    array (
      'Index' => 'Default',
      'Error' => 'Default',
      'Admin' => 'Default',
      'Site' => 'Default',
    ),
    'defaultLanguage' => 'en',
    'cookieEncryptionkey' => 'KkX+DVfEA>196yN',
    'cache' => 
    array (
      'lifetime' => 86400,
      'adapter' => 'File',
      'cacheDir' => ROOT_PATH . '/app/var/cache/data/',
    ),
    'logger' => 
    array (
      'enabled' => false,
      'path' => ROOT_PATH . '/app/var/logs/',
      'format' => '[%date%][%type%] %message%',
    ),
    'view' => 
    array (
      'compiledPath' => ROOT_PATH . '/app/var/cache/volt/',
      'compiledExtension' => '.php',
      'compiledSeparator' => '_',
      'compileAlways' => true,
    ),
    'session' => 
    array (
      'adapter' => 'Files',
    ),
    'assets' => 
    array (
      'local' => 'assets/',
    ),
    'metadata' => 
    array (
      'adapter' => 'Memory',
      'metaDataDir' => ROOT_PATH . '/app/var/cache/metadata/',
    ),
    'annotations' => 
    array (
      'adapter' => 'Memory',
      'annotationsDir' => ROOT_PATH . '/app/var/cache/annotations/',
    ),
    'user' => 
    array (
      'directory' => '/uploads/avatar/',
      'minsize' => 1000,
      'maxsize' => 1000000,
      'mimes' => 
      array (
        0 => 'image/gif',
        1 => 'image/jpeg',
        2 => 'image/jpg',
        3 => 'image/png',
      ),
      'sanitize' => true,
    ),
    'product_article' => 
    array (
      'directory' => '/uploads/product_article/',
      'minsize' => 1000,
      'maxsize' => 1000000,
      'mimes' => 
      array (
        0 => 'image/gif',
        1 => 'image/jpeg',
        2 => 'image/jpg',
        3 => 'image/png',
      ),
      'sanitize' => true,
      'isoverwrite' => false,
    ),
    'article_content' => 
    array (
      'directory' => '/uploads/article_content/',
      'minsize' => 1000,
      'maxsize' => 1000000,
      'mimes' => 
      array (
        0 => 'image/gif',
        1 => 'image/jpeg',
        2 => 'image/jpg',
        3 => 'image/png',
      ),
      'sanitize' => true,
      'isoverwrite' => false,
    ),
  ),
  'permission' => 
  array (
    1 => 
    array (
      'Core' => 
      array (
        0 => 'error/*',
        1 => 'index/index',
      ),
      'User' => 
      array (
        0 => 'admin/login',
        1 => 'error/*',
      ),
      'Category' => 
      array (
        0 => 'error/*',
      ),
      'Pcategory' => 
      array (
        0 => 'error/*',
      ),
      'Article' => 
      array (
        0 => 'error/*',
        1 => 'site/*',
      ),
      'Product' => 
      array (
        0 => 'error/*',
      ),
    ),
    5 => 
    array (
      'User' => 
      array (
        0 => 'error/*',
        1 => 'admin/*',
      ),
      'Core' => 
      array (
        0 => 'error/*',
        1 => 'index/*',
      ),
      'Category' => 
      array (
        0 => 'error/*',
        1 => 'admin/*',
      ),
      'Pcategory' => 
      array (
        0 => 'error/*',
        1 => 'admin/*',
      ),
      'Article' => 
      array (
        0 => 'error/*',
        1 => 'admin/*',
        2 => 'site/*',
      ),
      'Product' => 
      array (
        0 => 'error/*',
        1 => 'admin/*',
      ),
      'Slug' => 
      array (
        0 => 'error/*',
        1 => 'admin/*',
      ),
      'Company' => 
      array (
        0 => 'error/*',
        1 => 'admin/*',
      ),
    ),
    10 => 
    array (
      'Core' => 
      array (
        0 => 'error/*',
        1 => 'index/index',
        2 => 'index/dashboard',
      ),
      'User' => 
      array (
        0 => 'admin/login',
        1 => 'error/*',
      ),
      'Category' => 
      array (
        0 => 'error/*',
        1 => 'admin/index',
        2 => 'admin/create',
        3 => 'admin/edit',
      ),
      'Pcategory' => 
      array (
        0 => 'error/*',
        1 => 'admin/index',
        2 => 'admin/create',
        3 => 'admin/edit',
      ),
      'Article' => 
      array (
        0 => 'error/*',
        1 => 'site/*',
        2 => 'admin/index',
        3 => 'admin/create',
        4 => 'admin/edit',
        5 => 'admin/uploadimage',
        6 => 'admin/deleteimage',
      ),
      'Product' => 
      array (
        0 => 'error/*',
        1 => 'admin/index',
        2 => 'admin/create',
        3 => 'admin/edit',
        4 => 'admin/uploadimage',
        5 => 'admin/deleteimage',
      ),
    ),
    15 => 
    array (
      'Core' => 
      array (
        0 => 'error/*',
        1 => 'index/index',
      ),
      'User' => 
      array (
        0 => 'admin/login',
        1 => 'error/*',
      ),
      'Category' => 
      array (
        0 => 'error/*',
      ),
      'Pcategory' => 
      array (
        0 => 'error/*',
      ),
      'Article' => 
      array (
        0 => 'error/*',
        1 => 'site/*',
      ),
      'Product' => 
      array (
        0 => 'error/*',
      ),
    ),
  ),
  'events' => 
  array (
  ),
  'modules' => 
  array (
    0 => 'user',
    1 => 'category',
    2 => 'article',
    3 => 'pcategory',
    4 => 'product',
    5 => 'slug',
    6 => 'company',
  ),
);