<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                // '<controller:\w+>/<action:\w+>' => '<controller>/<action:\w+>',
                'oauth2/<action:\w+>' => 'oauth2/default/<action>',
                'shop/<action:\w+>' => 'shop/default/<action>',
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
                'access_lifetime' => 3600 * 24
            ],
            'storageMap' => [
                'user_credentials' => 'common\models\User'
            ]
        ]
    ]
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    if(YII_DEBUG){
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = 'yii\debug\Module';
    }
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
