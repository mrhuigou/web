<?php
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use api\models\V1\ReturnProduct;
/* @var $this yii\web\View */
$this->title = '商品评论';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = ['label' => '我的订单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
	<div class=" " style="min-width:1100px;">
		<div class="w1100 bc pt15">
			<!--面包屑导航-->
				<?= Breadcrumbs::widget([
				    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				    'tag'=>'p',
				    'options'=>['class'=>'gray6 mb15'],
				    'itemTemplate'=>'<a class="f14">{link}</a> > ',
				    'activeItemTemplate'=>'<a class="f14">{link}</a>',
				]) ?>
			<div class="  whitebg shadowBox clearfix gray6 w1100">
				<div class="box_u back_title">
					<p class="fw pl25 w330 f20">商品评论</p>
				</div>
				<div class=" row">
					<div class=" w300 fl ">
							<h3 class="graybg p10" >订单信息</h3>
							<ul class="p10 lh200">
								<li>订单编号：<?=$order->order_no?></li>
								<li>成交时间：<?=$order->date_added?></li>
								<?php foreach($order->orderTotals as $total){?>
								<li><?=$total->title?>：￥<?=$total->value?></li>
								<?php } ?>
							</ul>
					</div>
					<div class="fl  w760 bdl p10" style="min-height: 213px;">
						<?php if($order_product){ ?>
						<table width="100%" cellspacing="0" cellpadding="0" class="o_list">
							<tbody><tr class="graybg b_btm">
								<th width="33%" height="20" class="b_btm f14 first_th th">商品信息</th>
								<th width="13%" class="b_btm f14 th">单价</th>
								<th width="13%" class="b_btm f14 th">数量</th>
								<th width="13%" class="b_btm f14 th">金额</th>
								<th class="b_btm f14 ">优惠</th>
							</tr>
							<tr>
								<td width="33%" class="tl">
									<div class="clearfix">
									<div class="fl"><img width="60" height="60" class="bd mr15 db" src="<?= \common\component\image\Image::resize($order_product->product->image, 60, 60); ?>"></div>
									<div class="fl clearfix tl w150 mt5">
										<p><?=$order_product->name?></p>
										<p><?=$order_product->sku_name?></p>
									</div>
									</div>
									<?=frontend\widgets\Order\Promotion::widget(['product'=>$order_product])?>
								</td>
								<td width="13%">￥<?=$order_product->price?></td>
								<td width="13%"><?=$order_product->quantity?></td>
								<td width="13%">￥<?=$order_product->total?></td>
								<td class="last">￥<?=$order_product->total-$order_product->pay_total?></td>
							</tr>
							</tbody></table>
						<?php } ?>
						<?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
							'template' => "{label}<div class='mb10'>{input}</div>{error}",
							'inputOptions' => ['class' => 'bd w p5'],
							'labelOptions' => ['class' => 'p5  db w fb'],
							'errorOptions'=>['class'=>'red']
						],  ]);?>

						<?= $form->field($model, 'product_rating')->hiddenInput() ?>
						<div id="product_rating" data-rating="<?=$model->product_rating?>"></div>
						<?= $form->field($model, 'service_rating')->hiddenInput() ?>
						<div id="service_rating" data-rating="<?=$model->product_rating?>"></div>
						<?= $form->field($model, 'delivery_rating')->hiddenInput() ?>
						<div id="delivery_rating" data-rating="<?=$model->product_rating?>"></div>
						<?= $form->field($model, 'comment')->textarea()?>
						<?= $form->field($model, 'author')->checkbox()?>
						<?= \yii\helpers\Html::submitButton('发表评价', ['class' => 'btn mbtn  w greenbtn', 'name' => 'login-button']) ?>
						<?php ActiveForm::end(); ?>

					</div>
				</div>
			</div>
	</div>
</div>
<?php $this->beginBlock("JS_Block")?>
	$('#product_rating').raty({
	score: function() {
	return $(this).attr('data-rating');
	},
	target: '#reviewform-product_rating',
	targetKeep : true,
	targetType : 'number'
	});
	$('#service_rating').raty({
	score: function() {
	return $(this).attr('data-rating');
	},
	target: '#reviewform-service_rating',
	targetKeep : true,
	targetType : 'number'
	});
	$('#delivery_rating').raty({
	score: function() {
	return $(this).attr('data-rating');
	},
	target: '#reviewform-delivery_rating',
	targetKeep : true,
	targetType : 'number'
	});
<?php $this->endBlock()?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJsFile("/assets/script/jquery.raty.js",['depends'=>[\frontend\assets\AppAsset::className()]]);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);
