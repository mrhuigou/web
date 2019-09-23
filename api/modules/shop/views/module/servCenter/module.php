<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/1/19
 * Time: 15:24
 */
return ['display'=>[
    ['fieldLabel'=>'类型','xtype'=>'radiogroup','columns'=>'2','name'=>'define_style','items'=>[
        ['boxLabel'=>'默认','name'=>'define_style','inputValue'=>'1','checked'=>true],
        ['boxLabel'=>'自定义','name'=>'define_style','inputValue'=>'0','checked'=>false],
    ]],
    ['fieldLabel'=>'自定义内容','xtype'=>'htmleditor','maxLength'=>'500','name'=>'content','value'=>""]
],
    'content'=>[]
];