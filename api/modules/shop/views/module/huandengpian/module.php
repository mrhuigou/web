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
        ['fieldLabel'=>'切换效果','xtype'=>'combo','fields'=>['key','name'],'name'=>'order','value'=>"2",'data'=>[
            ['1','上下滚动'],
            ['2','左右滚动'],
        ]]
        ],
        'content'=>[
            ['name'=>'imagezone','xtype'=>'imagezone',"detail"=>[
                ['fieldLabel'=>'标题','xtype'=>'textfield','maxLength'=>'500','name'=>'title','value'=>""],
                ['fieldLabel'=>'图片地址','xtype'=>'image','maxLength'=>'500','name'=>'image','value'=>""],
                ['fieldLabel'=>'链接地址','xtype'=>'textfield','maxLength'=>'500','name'=>'href','value'=>"http://"]
            ]]
        ]
    ];