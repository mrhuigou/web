<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = '订单详情';
?>
<?= h5\widgets\Header::widget(['title' => $this->title]) ?>
<section class="veiwport  mb50">
	<div class="fool white clearfix">
		<div class="fl w f12 lh150">
			<p class="f14 mb5 clearfix">
				<span class="fl"> 订单号：<?= $model->order_no ?></span>
				<span class="fr"><?= $model->orderStatus->name; ?></span>
			</p>

			<p>订单金额(含运费):￥<?= number_format($model->total, 2); ?></p>
		</div>
	</div>

	<?php if (isset($model->orderShipping) && !empty($model->orderShipping)) { ?>
		<div class="fool-addr bdb p15 clearfix whitebg mb10">
			<div class="fl w f12 lh150">
				<p class="f14 mb5 clearfix">
					<span class="fr">电话:<?= $model->orderShipping->shipping_telephone; ?></span>
					<span class="f14">收货人:<?= $model->orderShipping->shipping_firstname; ?></span>
				</p>

				<p>
					地址：<?= $model->orderShipping->shipping_zone . $model->orderShipping->shipping_city . $model->orderShipping->shipping_district . $model->orderShipping->shipping_address_1; ?></p>

				<p>送货时间：<?= $model->orderShipping->delivery ?>  <?= $model->orderShipping->delivery_time ?></p>

				<p>备注：<?= $model->comment ? $model->comment : '无' ?></p>
			</div>
		</div>
	<?php } ?>
    <div class="mb10">
    <?php if (strtolower($model->order_type_code) == strtolower('groundpush') && $model->order_status_id==2 ) { ?>
        <?php $order_merage = \api\models\V1\OrderMerge::findOne(['order_ids'=>$model->order_id,'status'=>1,'customer_id'=>Yii::$app->user->getId()])?>
        <?php if($order_merage){?>
            <div class=" bdb  mb10 whitebg tc">
                <a class="btn greenbtn w p10 mt5" href="<?php echo Url::to(['checkout/complate','trade_no'=>$order_merage->merge_code])?>">查看自提二维码</a>
            </div>
        <?php }?>
    <?php } ?>
    </div>
	<div class="mb10">
		<?php if ($model->orderProducts) { ?>
            <table class="bg-wh w p10">
                <tr>
                    <td colspan="3"><h2 class="p10  bdb whitebg"><span class="vm">店铺：<?= $model->store->name; ?></span></h2></td>
                </tr>
	            <?php foreach ($model->orderProducts as $order_proudct) { ?>
                <tr>
                    <td class="p5" style="width: 20%">
                        <a class="db w"
                           href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$order_proudct->product_base_code,'shop_code'=>$model->store->store_code])?>">
                            <img
                                    src="<?= \common\component\image\Image::resize($order_proudct->product->image, 100, 100) ?>"
                                    class="bd w">
                        </a>
                    </td>
                    <td valign="top" class="p5 " style="width: 50%">
                        <h2 class="row-two"><?= $order_proudct->name; ?></h2>
                        <p class="gray9  mt2"><?= $order_proudct->product->getSku(); ?></p>
	                    <?= h5\widgets\Order\Promotion::widget(['product' => $order_proudct]) ?>
                    </td>
                    <td class="p5 tc bc">
                        <p class="gray6 mb5">X <em class="qty"><?= $order_proudct->quantity; ?></em></p>
                        <p class="red mb5">￥<?= floatval($order_proudct->total) ?></p>
                        <p class="red mb5"><em class="f12">实付</em>￥<?= floatval($order_proudct->pay_total) ?></p>
	                    <?php if (in_array($model->order_status_id, [2, 3, 5, 10, 13]) && !$model->use_points && strtolower($model->order_type_code) != 'groundpush') { ?>
                            <a class="btn   bd p5 mb5"
                               href="<?= \yii\helpers\Url::to(['/order/refund', 'order_no' => $model->order_no, 'item_id' => $order_proudct->order_product_id], true) ?>">
			                    <?php if($order_proudct->quantity==$order_proudct->getRefundQty()){?>查看售后<?php }else{ ?> 申请售后<?php }?>
                            </a>
	                    <?php } ?>
                    </td>
                </tr>
	            <?php } ?>
            </table>

		<?php } ?>
		<?php if ($model->orderDigitalProducts) { ?>
			<?php foreach ($model->orderDigitalProducts as $op) { ?>
				<div class="flex-col flex-center store-item bdb  whitebg">
					<div class="flex-item-2 flex-row flex-middle flex-center p5">
						<i class="iconfont red f50 lh44">&#xe628;</i>
					</div>
					<div class="flex-item-6 flex-row  flex-left item-info p5">
						<h2><?= $op->model; ?></h2>

						<p class="gray9"><?= $op->name; ?> </p>
						<?php if ($op->type == 'telephone') { ?>
							<p class="gray9"><?= $op->account; ?> </p>
						<?php } ?>
					</div>
					<div class="flex-item-4 flex-row flex-middle flex-center item-price">
						<p>￥<?php echo number_format($op['price'], 2); ?></p>

						<p class="gray6">x <em class="qty"><?php echo $op['qty']; ?></em></p>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
		<?= h5\widgets\Order\OrderPromotion::widget(['order' => $model]) ?>
	</div>
	<?=h5\widgets\Order\OrderTotal::widget(['order_total'=>$model->orderTotals])?>
    <?php if($model->use_points){?>
    <div class="whitebg bdt bdb p10 lh180 f12 mb10">
        <em class="f12 red">【注：积分抵扣的订单不支持退货，如果发现质量问题，请联系客服为您换货，客服电话： <?= Yii::$app->common->getSiteMobile() ?> 】</em>
    </div>
    <?php }?>
	<div class="whitebg bdt bdb p10 lh180 f12 mb50">
		订单号：<?= $model->order_no ?> <br>
		下单时间：<?= $model->date_added ?> <br>
		<?php if ($model->payment_deal_no) { ?>
			支付方式：<?= $model->payment_method ?><br>
			交易流水号：<?= $model->payment_deal_no ?><br>
		<?php } ?>
		发票信息：<?= $model->invoice_temp; ?> <?php if (!empty($model->invoice_title)) {
			echo ":" . $model->invoice_title;
		} ?>
	</div>

	<div class=" tc  fx-bottom whitebg bdt p10" style="z-index: 1000">
		<?php if(in_array($model->order_type_code,['normal','presell'])){?>
		<?php if ($model->order_status_id == 1) { ?>
			<a class="btn sbtn redbtn pr10 pl10"
			   href="<?= \yii\helpers\Url::to(['/order/pay', 'order_no' => $model->order_no], true) ?>"> 支付 </a>
                <a class="fr btn sbtn graybtn pr10 pl10 cancel-order" href="#" data-content="<?=$model->order_no?>"> 取消订单</a>
<!--			--><?//= Html::a('取消订单', ['cancel', 'order_no' => $model->order_no], [
//				'class' => 'fr btn sbtn graybtn pr10 pl10 ',
//				'data' => [
//					'confirm' => '您确认要取消吗？',
//					'method' => 'post',
//				],
//			]) ?>
		<?php } else { ?>
			<?php if (in_array($model->order_status_id, [2, 3, 5, 9, 10, 13]) && !$model->use_points) { ?>
				<a class="btn sbtn bluebtn pr10 pl10"
				   href="<?= \yii\helpers\Url::to(['/order/refund-all', 'order_no' => $model->order_no], true) ?>">
					整单退 </a>
			<?php } ?>
			<a class="btn sbtn orgbtn pr10 pl10"
			   href="<?= \yii\helpers\Url::to(['/order/add-cart', 'order_no' => $model->order_no], true) ?>"> 再次购买 </a>
		<?php } ?>
		<?php }else{?>
		<?php if ($model->order_status_id == 1) { ?>
			<a class="btn sbtn redbtn pr10 pl10"
			   href="<?= \yii\helpers\Url::to(['/order/pay', 'order_no' => $model->order_no], true) ?>"> 支付 </a>
                <a class="fr btn sbtn graybtn pr10 pl10 cancel-order" href="#" data-content="<?=$model->order_no?>"> 取消订单</a>
<!--			--><?//= Html::a('取消订单', ['cancel', 'order_no' => $model->order_no], [
//				'class' => 'fr btn sbtn graybtn pr10 pl10 ',
//				'data' => [
//					'confirm' => '您确认要取消吗？',
//					'method' => 'post',
//				],
//			]) ?>
		<?php } else { ?>
				<a class="btn sbtn graybtn pr10 pl10"  href="javascript:history.back();"> 返回 </a>
			<?php } ?>
		<?php } ?>
	</div>

</section>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        $(".cancel-order").click(function(){
            var _this = $(this);
            var order_no = _this.attr('data-content');
            $.confirm("您确认要取消吗?",'友情提示',function(){
                $.get('<?php echo \yii\helpers\Url::to(["/order/cancel"])?>',{order_no:order_no},function(){
                });
            });
        });
        <?php $this->endBlock() ?>
    </script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>