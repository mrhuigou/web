<?php
return [
    'adminEmail' => 'admin@365jiarun.com',
    'supportEmail' => 'support@365jiarun.com',
    'user.passwordResetTokenExpire' => 3600,
    //'HTTP_IMAGE'=>'https://img1.365jiarun.com/',
    'HTTP_IMAGE'=>(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ) ? "https://img1.365jiarun.com/" :  "http://img1.365jiarun.com/",
//    'ERP_SOAP_URL'  =>"http://wms.365jiarun.com:7023/jiarunWebServer/services/web2sys?wsdl",//
    'ERP_SOAP_URL'=>'http://192.168.1.224:8080/jiarunWebServer/services/web2sys?wsdl',
    'API_URL' => 'http://api.jiarun.com', // https://open.365jiarun.com
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
