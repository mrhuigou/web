<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='支付结果';
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/site/index']) ?>">
            <i class="iconfont">&#xe63f;</i>
        </a>
        <div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/user/index']) ?>">
            <i class="aui-icon aui-icon-people green f28"></i>
        </a>
    </div>
</header>
<section class="pt50">
    <div class="bg-wh">
        <img src="/assets/images/order-ok3.png" class="w">
        <div class="tc pb5 " style="margin-top: -12px;">
	        <?php if($model->status){ ?>
            <h2 class="f24 green">支付成功</h2>
            <p class="f18 gray3 mt10 ">付款金额：￥<?=floatval($model->total)?></p>
	        <?php }else{ ?>
            <h2 class="f24 red">支付失败</h2>
            <p class="f18 gray9 mt10 mb10">付款金额：￥<?=floatval($model->total)?></p>
            <p class="gray9 mb10">支付遇到问题请联系客服 </p>
            <a class="btn btn-m btn-bd-green btn-pill" href="tel:<?= Yii::$app->common->getSiteMobile() ?>">联系客服</a>
	        <?php } ?>
        </div>
        <div class="tc pb5 " >
        <?php if($orders = $model->order){?>

            <?php foreach ($orders as $order){
                if(strtolower($order->order_type_code) == 'groundpush'){ ?>
                    <h2 class="f12 green">提货码：</h2>
                    <img class="tc" src="<?php echo \yii\helpers\Url::to(['/ground-push/get-qrcode','order_id'=>$order->order_id],true)?>">
            <?php }
            }?>
        <?php }?>
        </div>
    </div>
	<?=\h5\widgets\Order\Complate::widget(['model'=>$model])?>
</section>
<?=\h5\widgets\Tools\Share::widget([
	'data'=>[
		'title' => '红包来袭，手慢无！！！',
		'desc' => "家润每日惠购网，物美价廉，当日订单，当日送达。",
		'link' => \yii\helpers\Url::to(['/share/subscription'],true),
		'image' => 'https://m.mrhuigou.com/images/gift-icon.jpg'
	]
])?>

