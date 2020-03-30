<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title ='售后订单';
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  pb50">
    <ul class="filter  redFilter three bdb clearfix">
        <li class="<?php if($order_status==1){ echo "cur";}?>"><a href="<?=Url::to(['/order/return','order_status'=>'1'],true)?>">退货</a></li>
        <li class="<?php if($order_status==2){ echo "cur";}?>"><a href="<?=Url::to(['/order/return','order_status'=>'2'])?>">退款</a></li>
        <li class="<?php if($order_status==3){ echo "cur";}?>"><a href="<?=Url::to(['/order/return','order_status'=>'3'])?>">换货</a></li>
    </ul>
    <?php
    echo \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'emptyText'=>'<figure class="info-tips whitebg gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有信息</figcaption></figure>',
        'itemView' => '_item_view_return',
        'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
            'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
            'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
            'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
            'eventOnRendered' => 'function() { $("img.lazy").scrollLoading();}',
        ]
    ]);
    ?>
</section>
<?= h5\widgets\MainMenu::widget(); ?>