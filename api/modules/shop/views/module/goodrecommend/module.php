<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/1/19
 * Time: 15:24
 */
return ['display'=>[
    ['fieldLabel'=>'标题','xtype'=>'textfield','maxLength'=>'500','name'=>'title','value'=>"宝贝推荐"],
    ['fieldLabel'=>'显示方式','xtype'=>'radiogroup','columns'=>'2','name'=>'display_style','items'=>[
        ['boxLabel'=>'一行两列','name'=>'category_menu','inputValue'=>'2','checked'=>false],
        ['boxLabel'=>'一行三列','name'=>'category_menu','inputValue'=>'3','checked'=>true],
        ['boxLabel'=>'一行四列','name'=>'category_menu','inputValue'=>'4','checked'=>false],
    ]],
    ['fieldLabel'=>'排序方式','xtype'=>'combo','fields'=>['key','name'],'name'=>'order','value'=>"2",'data'=>[
        ['1','人气指数'],
        ['2','商品销量'],
    ]],
],
    'content'=>[
        ['name'=>'product_list','xtype'=>'productzone']
    ]
];