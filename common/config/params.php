<?php
return [
    'adminEmail' => 'admin@mrhuigou.com',
    'supportEmail' => 'support@mrhuigou.com',
    'user.passwordResetTokenExpire' => 3600,
    'HTTP_IMAGE'=>(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://img1.mrhuigou.com/" :  "http://img1.mrhuigou.com/",
//    'ERP_SOAP_URL'  =>"http://wms.mrhuigou.com:7023/jiarunWebServer/services/web2sys?wsdl",//
    'ERP_SOAP_URL'=>'http://192.168.1.224:8080/mrhgWebServer/services/web2sys?wsdl',
    'FDFS'=>[
        'tracker_addr'=>'119.167.153.84',
        'tracker_port'=>'22122',
        'group_name'=>'group1',
    ],
    'weixin'=>[
        'token'=>'1q2w3e4r',
        'appid'=>'wxa8b041c2bffdd399',
        'appsecret'=>'f1e244e247fb68971694079fd87aed26',
        'EncodingAESKey'=>''
    ],
//	'open_search'=>[ //测试搜索
//		'access_key' => "LTAIfKia77AoA9KE",
//		'secret' => "uMZfXhHJApswQtjEMasIoWjiHEOVj2"
//	],
    'open_search'=>[//正式搜索
        'access_key' => "LTAIBE7y6V4iRrj6",
        'secret' => "JqXIuplK2MyVgFsIUDGyuQ5b0JLsH8"
    ],
];
