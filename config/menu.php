<?php

return [
/*
 * Provider .
 */
    'provider' => 'lavalite',
/*
 * Package .
 */
    'package'  => 'menu',
/*
 * Modules .
 */
    'modules'  => ['menu'],

    'image'    => [
        'xs' => ['width' => '60', 'height' => '45', 'default' => ''],
        'sm' => ['width' => '160', 'height' => '75'],
        'md' => ['width' => '460', 'height' => '345'],
        'lg' => ['width' => '800', 'height' => '600'],
        'xl' => ['width' => '1000', 'height' => '750'],
    ],

    'menu'     => [
        'name'          => 'Menu',
        'table'         => 'menus',
        'model'         => 'Litepie\Menu\Models\Menu',
        'fillable'      => ['parent_id', 'key', 'url', 'icon', 'permission', 'name', 'description', 'target', 'order', 'status'],
        'listfields'    => ['id', 'name', 'level', 'url', 'order', 'icon', 'status', 'type', 'target', 'key'],
        'upload-folder' => 'uploads/menu',
        'uploadable'    => [
            'single'   => [],
            'multiple' => [],
        ],
    ],
];
