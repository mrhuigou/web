<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-affiliate',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'affiliate\controllers',
    'language' =>'zh-CN',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'api\models\V1\Affiliate',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => 'affiliate_identity'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site-mobile/error',
        ],
    ],
    'params' => $params,
    'on beforeAction'=>['affiliate\events\InitShare','assign'],
];
