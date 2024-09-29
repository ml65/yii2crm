<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@backend'     => dirname(dirname(__DIR__)) . '/backend',
        '@backendUrl'  => 'https://y2aa-backend.ml65.spb.ru',
        '@frontend'    => dirname(dirname(__DIR__)) . '/frontend',
        '@frontendUrl' => 'https://y2aa-frontend.ml65.spb.ru',
        '@console'     => dirname(dirname(__DIR__)) . '/console'

    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
    ],
];
