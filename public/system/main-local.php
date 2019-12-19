<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db03;dbname=jiarun_web_live',
            'username' => 'jiarun',
            'password' => 'jiarun#365',
            'charset' => 'utf8',
            'tablePrefix' => 'jr_',
        ],
 	'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '192.168.1.239:9200'],
                // configure more hosts if you have a cluster
            ],
        ],
        'cart' => [
            'class' => 'common\component\cart\ShoppingCart',
            'cartId' => 'my_application_cart',
        ],
         'redis' => [
             'class' => 'yii\redis\Connection',
             'hostname' => '192.168.1.247',
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
            'useFileTransport' => false,
	    'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mrhuigou.com',
                'username' => 'adm@mrhuigou.com',
                'password' => 'adm321123',
                'port' => '25',
               // 'encryption' => 'ssl',
            ],
        ],
	'wechat' => [
		    'class' => 'common\component\weixin\Wechat',
		    'appId' => 'wx538bb8de1b0f1074',
		    'appSecret' => 'dcedb30a7ec916a09a9fc5e82fbbd6f7',
		    'token' => '1q2w3e4r'
	    ],
    ],
];
