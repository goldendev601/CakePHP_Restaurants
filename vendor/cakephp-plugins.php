<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        //'.svn' => $baseDir . '/plugins/.svn/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'RestApi' => $baseDir . '/vendor/multidots/cakephp-rest-api/',
        'V1' => $baseDir . '/plugins/V1/'
    ]
];