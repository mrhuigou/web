<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone'=>'Asia/Chongqing',
    'name'=>'每日惠购',
    'components' => [
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '192.168.0.6:9200'],
                // configure more hosts if you have a cluster
            ],
        ],

    ],
    // 加载全局 自定义 函数类
    // Used Yii::$app->common
    'common' => [
        'class' => 'common\components\Common',
        'property' => '123',
    ],
];
