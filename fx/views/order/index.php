<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title ='订单中心';
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
    <section class="veiwport  pb50">
    <?php $order_status = Yii::$app->request->get('order_status');?>
    <ul class="filter  redFilter four bdb clearfix">
        <li  class="<?php if($order_status==''){ echo "cur";}?>"><a href="<?=Url::to(['/order/index'],true)?>">全部</a></li>
        <li class="<?php if($order_status=='NOPAY'){ echo "cur";}?>"><a href="<?=Url::to(['/order/index','order_status'=>'NOPAY'],true)?>">待付款</a></li>
        <li class="<?php if($order_status=='PAYED'){ echo "cur";}?>"><a href="<?=Url::to(['/order/index','order_status'=>'PAYED'])?>">待发货</a></li>
        <li class="<?php if($order_status=='ONWAY'){ echo "cur";}?>"><a href="<?=Url::to(['/order/index','order_status'=>'ONWAY'])?>">待收货</a></li>
    </ul>
        <?php
        echo \yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'emptyText'=>'<figure class="info-tips whitebg gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有信息</figcaption></figure>',
            'itemView' => '_item_view',
            'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
                'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
                'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
                'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
                'eventOnRendered' => 'function() { $("img.lazy").scrollLoading();}',
            ]
        ]);
        ?>
</section>
<!--    --><?//= fx\widgets\MainMenu::widget(); ?>

