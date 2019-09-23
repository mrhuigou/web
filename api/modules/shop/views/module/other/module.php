<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/1/19
 * Time: 15:24
 */
return ['display'=>[
    ['fieldLabel'=>'显示标题','xtype'=>'radiogroup','columns'=>'2','name'=>'display','items'=>[
        ['boxLabel'=>'不显示','name'=>'display','inputValue'=>'0','checked'=>true],
        ['boxLabel'=>'显示','name'=>'display','inputValue'=>'1','checked'=>false],
    ]],
    ['fieldLabel'=>'自定义内容','xtype'=>'htmleditor','maxLength'=>'500','name'=>'content','value'=>""]
],
    'content'=>[]
];