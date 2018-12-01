<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5JNBCnpJslw8Kgp3mj5xI2b-5ffLPBhw',

        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => ['/assets/script/jq.min.js']
                ],
                'yii\web\YiiAsset' => [
                    'sourcePath' => null,
                    'js' => ['/assets/js/core/yii.js']
                ],
                'yii\widgets\ActiveFormAsset' => [
                    'sourcePath' => null,
                    'js' => ['/assets/js/core/yii.activeForm.js']
                ],
                'yii\validators\ValidationAsset' => [
                    'sourcePath' => null,
                    'js' => ['/assets/js/core/yii.validation.js']
                ],
                'yii\captcha\CaptchaAsset' => [
                    'sourcePath' => null,
                    'js' => ['/assets/js/core/yii.captcha.js']
                ],
                'yii\authclient\widgets\AuthChoiceStyleAsset' => [
                    'sourcePath' => null,
                    'js' => ['/assets/js/authchoice/authchoice.js'],
                    'css' => ['/assets/js/authchoice/authchoice.css']
                ],
                'kop\y2sp\assets\InfiniteAjaxScrollAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        '/assets/js/callbacks.js',
                        '/assets/js/jquery-ias.js',
                        '/assets/js/extension/history.js',
                        '/assets/js/extension/noneleft.js',
                        '/assets/js/extension/paging.js',
                        '/assets/js/extension/spinner.js',
                        '/assets/js/extension/trigger.js'
                    ]
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            // 'suffix'   => ".html",
            'rules' => [
                '<shop_code:\w+>-<product_code:\w+>.html' => 'product/index',
                'page/<page_id:\w+>.html' =>'page/index', //
                'information/<information_id:\w+>.html' =>'information/information', //
                'category' => 'search/index',
                'category/index' => 'search/index',
                'product/category' => 'search/index',
                'store/search' => 'search/index',
                'subject/<subject:\w+>.html' => 'topic/index',
                'act/<code:\w+>.html' => 'topic/detail',
                'promotion/<subject:\w+>.html' => 'promotion/index',
                'mall/<action:\w+>' => 'mall/default/<action>',
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'WeiXin' => [
                    'class' => 'common\component\oauth\WeixinAuth',
                    'clientId' => 'wxafd73379315fdb5f',
                    'clientSecret' => 'a6999665a06d3a3c064c5deb4b2d1da7',
                ],
                'Sina' => [
                    'class' => 'common\component\oauth\WeiboAuth',
                    'clientId' => '2050277071',
                    'clientSecret' => '0a37b9c0adeebdd6f56fb3fe65b4a42b',
                ],
                'QQ' => [
                    'class' => 'common\component\oauth\QqAuth',
                    'clientId' => '100484507',
                    'clientSecret' => '5f6d5e9c23c1bbb102f26e7382075f5b',
                ],
            ]
        ]
    ],
    'modules'=>[
        'mall'=>[
            'class' => 'frontend\modules\mall\Module',
        ],
        'club' => [
            'class' => 'frontend\modules\club\Module',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = 'yii\debug\Module';
//
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*'],
    ];
}

return $config;
