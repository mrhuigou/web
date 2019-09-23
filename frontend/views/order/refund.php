<?php
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use api\models\V1\ReturnProduct;
/* @var $this yii\web\View */
$this->title = '申请退货';
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
					<p class="fw pl25 w330 f20">退货管理</p>
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
						<?=$form->field($model,'return_model')->dropDownList(['RETURN_GOODS'=>'退货','RESHIP'=>'换货','RETURN_PAY'=>'仅退款'])?>
						<?= $form->field($model, 'qty',['inputOptions' => ['class' => 'bd p5','max'=>$order_product->quantity-$order_product->refund_qty,'min'=>1]]); ?>
						<p class="gray9">您最多可提交数量为<?=max(0,$order_product->quantity-$order_product->refund_qty)?>个</p>
						<?= $form->field($model, 'comment')->textarea()?>
						<?= $form->field($model, 'username') ?>
						<?= $form->field($model, 'telephone') ?>
						<?= $form->field($model, 'paymethod')->radioList(['0'=>'退回账户余额','1'=>'原支付方式返回']); ?>
						<?= \yii\helpers\Html::submitButton('提交申请', ['class' => 'btn mbtn  w greenbtn', 'name' => 'login-button']) ?>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		<div class="yellowBox p10 mt10">
			<h3 class="f14 fb">退货说明：</h3>
			<div class="line_dash mt5 mb5"></div>
			<p>1. 外包装及附件赠品，退货时请一并退回，如有破损或丢失，请自行寻找相似包装代替。</p>
			<p>2.关于物流损：请您在收货时务必仔细验货，如发现商品外包装或商品本身外观存在异常，需当场向配送人员指出，并拒收整个包裹；如您在收货后发现外观异常，请在收货24小时内提交退货申请。如超时未申请，将无法受理。
			</p>
			<p>3.关于商品实物与网站描述不符：家润商城保证所出售的商品均为正品行货，并与时下市场上同样主流新品一致。但因厂家会在没有任何提前通知的情况下更改产品包装、产地或者一些附件，所以我们无法确保您收到的货物与家润商城图片、产地、附件说明完全一致。</p>
			<p>4. 如果您在使用时对商品质量表示置疑，您可出具相关书面鉴定，我们会按照国家法律规定予以处理。</p>
		</div>
	</div>
</div>
