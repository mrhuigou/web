<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.1.224;dbname=jiarun_live',
            'username' => 'stagging',
            'password' => 'stagging',
            'charset' => 'utf8',
            'tablePrefix' => 'jr_',
        ],
        'solr' => [
            'class' => 'common\component\solr\Client',
            'options' => [
                'endpoint' => [
                    'solr1' => [
                        'host' => '192.168.1.224',
                        'port' => '9083',
                        'path' => '/solr/product_live'
                    ]
                ]
            ]
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '192.168.1.224',
            'port' => 6379,
            'database' => 0,
        ],
        'session' => [
            'class' => 'yii\redis\Session',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
