<?php
return [
    'adminEmail' => 'admin@mrhuigou.com',
    'supportEmail' => 'support@mrhuigou.com',
    'user.passwordResetTokenExpire' => 3600,
//    'HTTP_IMAGE'=>"https://img1.mrhuigou.com/",
    'HTTP_IMAGE'=>(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ) ? "https://img1.mrhuigou.com/" :  "http://img1.mrhuigou.com/",
    'ERP_SOAP_URL'=>'http://wms.mrhuigou.com:7023/mrhgWebServer/services/web2sys?wsdl',
	'API_URL' => 'https://open.mrhuigou.com',
    'FDFS'=>[
        'tracker_addr'=>'192.168.1.240',
        'tracker_port'=>'22122',
        'group_name'=>'group1',
    ],
    'weixin'=>[
        'token'=>'1q2w3e4r',
        'appid'=>'wx538bb8de1b0f1074',
        'appsecret'=>'dcedb30a7ec916a09a9fc5e82fbbd6f7',
        'EncodingAESKey'=>'FIzkvAMXT7RcTGM0l1SenRxUAr8cwIy6wsP11NR8Qdn'
    ],
    'open_search'=>[
	'access_key' => "LTAIBE7y6V4iRrj6",
	'secret' => "JqXIuplK2MyVgFsIUDGyuQ5b0JLsH8"
    ],
];
