<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'llWTLRjSjOF_n9fir7rOCGX40T3rL6UA',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false
        ],
        'authManager'=>[
            'class' => 'yii\rbac\DbManager',//认证类名称
            'defaultRoles'=>['guest'],//默认角色
            'itemTable' => 'jr_auth_item',//认证项表名称
            'assignmentTable' => 'jr_auth_assignment',//认证项父子关系
            'itemChildTable' => 'jr_auth_item_child',//认证项赋权关系
            'ruleTable'=>'jr_auth_rule'
        ],
    ],
];


return $config;
