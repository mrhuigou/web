<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5JNBCnpJslw8Kgp3mj5xI2b-5ffLPBhw',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '<shop_code:\w+>-<product_code:\w+>.html' => 'product/index',
                'page/<page_id:\w+>.html' =>'page/index', //
                'information/<information_id:\w+>.html' =>'information/information', //
                'subject/<subject:\w+>.html' => 'topic/index',
                'act/<code:\w+>.html' => 'topic/detail',
                'promotion/<subject:\w+>.html' => 'promotion/index',
                'product/category' => 'category/index',
                'shop/search'=>'search/index'
            ],

        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => ['/assets/js/jq.min.js','/assets/script/jquery-migrate-1.2.1.js']
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
    ],
];

return $config;
