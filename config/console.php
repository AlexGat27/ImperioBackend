<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // Использование DbManager для хранения данных RBAC в базе данных
            'itemTable' => 'auth_roles', // новое имя таблицы для ролей и разрешений
            'itemChildTable' => 'auth_item_childs', // новое имя таблицы для связей ролей и разрешений
            'assignmentTable' => 'auth_assignments', // новое имя таблицы для назначений ролей пользователям
            'ruleTable' => 'auth_rules', // новое имя таблицы для правил
        ],
    ],
    'params' => $params,
    'controllerMap' => [
        'rbac' => [
            'class' => 'app\commands\RbacController',
        ],
        'root-user' =>[
            'class' => 'app\commands\RootUserController',
        ]
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    // configuration adjustments for 'dev' environment
    // requires version `2.1.21` of yii2-debug module
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
