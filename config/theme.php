<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default
    |--------------------------------------------------------------------------
    |
    | Default theme used for the website.
    |
     */

    'default'       => [
        'theme'  => 'public',
        'layout' => 'default',
        'blank'  => 'blank',
    ],

    /*
    |--------------------------------------------------------------------------
    | Themes
    |--------------------------------------------------------------------------
    |
    | Themes used for the website.
    | eg. admin, public, user etc.
    |
     */

    'themes'        => [
        'admin'  => [
            'theme'  => 'admin',
            'layout' => 'default',
            'blank'  => 'blank',
        ],
        'public' => [
            'theme'  => 'public',
            'layout' => 'default',
            'blank'  => 'blank',
        ],
        'user'   => [
            'theme'  => 'public',
            'layout' => 'user',
        ],
        'client' => [
            'theme'  => 'public',
            'layout' => 'client',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset url path
    |--------------------------------------------------------------------------
    |
    | The path to asset, this config can be cdn host.
    | eg. http://cdn.domain.com
    |
     */

    'assetUrl'      => '',

    /*
    |--------------------------------------------------------------------------
    | Theme Default
    |--------------------------------------------------------------------------
    |
    | If you don't set a theme when using a "Theme" class the default theme
    | will replace automatically.
    |
     */

    'themeDefault'  => 'public',

    /*
    |--------------------------------------------------------------------------
    | Layout Default
    |--------------------------------------------------------------------------
    |
    | If you don't set a layout when using a "Theme" class the default layout
    | will replace automatically.
    |
     */

    'layoutDefault' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Path to lookup theme
    |--------------------------------------------------------------------------
    |
    | The root path contains themes collections.
    |
     */

    'themeDir'      => 'themes',

    /*
    |--------------------------------------------------------------------------
    | A pieces of theme collections
    |--------------------------------------------------------------------------
    |
    | Inside a theme path we need to set up directories to
    | keep "layouts", "assets" and "partials".
    |
     */

    'containerDir'  => [
        'layout'  => 'layouts',
        'asset'   => 'assets',
        'partial' => 'partials',
        'widget'  => 'widgets',
        'view'    => 'views',
    ],

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    | Class namespace.
    |
     */

    'namespaces'    => [
        'widget' => 'App\Widgets',
    ],

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
     */

    'events'        => [],

    /*
    |--------------------------------------------------------------------------
    | Compiler engines.
    |--------------------------------------------------------------------------
    |
    | Config for compiler engines.
    |
     */

    'engines'       => [

        'twig' => [

            // This is laravel alias to allow in twig compiler
            // The list all of methods is at /app/config/app.php
            'allows' => [
                'Auth',
                'Cache',
                'Config',
                'Cookie',
                'Form',
                'HTML',
                'Input',
                'Lang',
                'Paginator',
                'Str',
                'Theme',
                'URL',
                'Validator',
            ],

            // This is laravel alias to allow in twig compiler
            // The list all of methods is at /app/config/app.php
            'hooks'  => [],
        ],

    ],

];
