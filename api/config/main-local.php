<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'XrFMUjA_6q73CcpMHZFpWCazAWPWL2FP',
            'enableCsrfValidation'=>false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                // '<controller:\w+>/<action:\w+>' => '<controller>/<action:\w+>',
                'oauth2/<action:\w+>' => 'oauth2/default/<action>',
                'shop/<action:\w+>' => 'shop/default/<action>',
                '<shop_code:\w+>-<product_code:\w+>.html' => 'product/index',
            ],
        ],
    ],
    'modules'=>[
        'shop'=>[
            'class' => 'api\modules\shop\Module',
        ],
        'oauth2'=>[
            'class' => 'api\modules\oauth2\Module',
            'options' => [
                'token_param_name' => 'accessToken',
                'access_lifetime' => 3600*24,
                'refresh_token_lifetime' => 3600*24*30*12*10,
            ],
            'storageMap' => [
                'user_credentials' => 'api\models\V1\Customer'
            ]
        ]
    ]
];

return $config;
