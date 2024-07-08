<?php

use yii\filters\Cors;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'enableCookieValidation' => false, // Отключаем валидацию cookie, так как не используем cookie
            'enableCsrfValidation' => false, // Отключаем CSRF-защиту, так как не используем cookie
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'user' => [
            'identityClass' => 'app\models\User', // Класс модели пользователя
            'enableAutoLogin' => true, // Отключаем автоматический логин через cookie
            'enableSession' => true, // Отключаем использование сессий для хранения состояния пользователя
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@runtime/logs/app.log', // файл логов
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'POST api/v1/login' => 'user/login',
                'GET api/v1/logout' => 'user/logout',
                'GET api/v1/users/profile' => 'user/profile',
                'POST api/v1/users/create' => 'user/create',
                'POST api/v1/users/update' => 'user/update',
                'POST api/v1/users/delete' => 'user/delete',
            ],
        ],
        'jwt' => [
            'class' => \Lcobucci\JWT\Signer\Hmac\Sha256::class,
            'key' => 'Imperio-Secret-Key', // Замените на ваш секретный ключ
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'savePath' => '@runtime/sessions',
            'useCookies' => true,
            'cookieParams' => [
                'httpOnly' => false,
                'secure' => false,
                'sameSite' => \yii\web\Cookie::SAME_SITE_NONE,
            ],
        ],
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
