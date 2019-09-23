<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \common\component\image\Image;
/* @var $this yii\web\View */
$this->title =$shop->name.'-店铺搜索';
?>

<section class="veiwport mt0">
    <!--~~ 筛选 ~~-->
    <ul class="filter  redFilter four clearfix" style="border-bottom: 2px solid #ff463c;">
        <li class="<?=$sort_selected=='score'?'cur':''?>"><a href="<?=$sort_data['score']?>">综合</a></li>
        <li class="<?=$sort_selected=='record'?'cur':''?>"><a href="<?=$sort_data['record']?>">销量</a></li>
        <li class="<?=$sort_selected=='price'?'cur ':' '?>"><a href="<?=$sort_data['price']?>" class="vm">价格<i class="iconfont vm f12 white" >
                    <?=Yii::$app->request->get('order')=="desc"?"&#xe61b;":"&#xe619;"?></i></a></li>
        <li ><a href="<?=Url::to(['/shop/category','shop_code'=>$shop->store_code])?>" ><span class="bc iconfont w50 vm" >&#xe600;</span><span class="vm">分类</span> </a></li>

    </ul>
    <ul class="pro-list2 ">
        <?= \yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'list-view'],
            'itemOptions' => ['class' => 'item '],
            'emptyTextOptions' => ['class' => 'empty tc p10 '],
            'itemView' => '_item_view_shop_search',
            'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
                'triggerTemplate'=>'<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
                'noneLeftTemplate'=>'<div class="ias-noneleft tc p10">{text}</div>',
                'noneLeftText'=>'已经到底了'
            ]
        ]);
        ?>
    </ul>
</section>
<?=\h5\widgets\StoreMainMenu::widget(['code'=>$shop->store_code])?>