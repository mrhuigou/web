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
    ]]
],
    'content'=>[
        ["name"=>"daohang","xtype"=>"navigator","detail"=>[
        ['fieldLabel'=>'标题','xtype'=>'textfield','maxLength'=>'500','name'=>'title','value'=>""],
        ['fieldLabel'=>'链接地址','xtype'=>'textfield','maxLength'=>'500','name'=>'href','value'=>"http://"],
        ]]
    ]
];