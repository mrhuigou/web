<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/1/19
 * Time: 15:24
 */
return ['display'=>[
    ['fieldLabel'=>'分类导航','xtype'=>'radiogroup','columns'=>'2','name'=>'category_menu','items'=>[
        ['boxLabel'=>'打开','name'=>'category_menu','inputValue'=>'1','checked'=>true],
        ['boxLabel'=>'关闭','name'=>'category_menu','inputValue'=>'0','checked'=>false],
    ]],
    ['fieldLabel'=>'显示方式','xtype'=>'radiogroup','columns'=>'2','name'=>'display_style','items'=>[
        ['boxLabel'=>'一行三列','name'=>'category_menu','inputValue'=>'3','checked'=>true],
        ['boxLabel'=>'一行四列','name'=>'category_menu','inputValue'=>'4','checked'=>false],
        ['boxLabel'=>'一行五列','name'=>'category_menu','inputValue'=>'5','checked'=>false],
    ]],
    ['fieldLabel'=>'排序方式','xtype'=>'combo','fields'=>['key','name'],'name'=>'order','value'=>"2",'data'=>[
        ['1','人气指数'],
        ['2','商品销量'],
    ]],
    ['fieldLabel'=>'每页展示宝贝数','xtype'=>'combo','fields'=>['key','name'],'name'=>'page','value'=>"1",'data'=>[
        ['1','24'],
        ['2','30'],
    ]],
    ],
    'content'=>[
        ['name'=>'product_list','xtype'=>'productzone']
    ]
];