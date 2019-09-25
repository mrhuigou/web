<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [ 
    'id' => 'app-h5',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' =>'zh-CN',
    'controllerNamespace' => 'h5\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => '.' . DOMAIN],
        ],
        'session' => [
            'cookieParams' => ['domain' => '.' . DOMAIN, 'lifetime' => 0],
            'timeout' => 3600,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'trace','warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => '/site/error',
        ],
    ],
    'params' => $params,
	'on beforeAction'=>['h5\events\InitShare','assign'],
];
