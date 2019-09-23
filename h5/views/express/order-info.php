<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/15
 * Time: 11:36
 */
$this->title="订单详情";
?>
<header class="fx-top bs-bottom whitebg lh44">
	<div class="flex-col tc">
		<a class="flex-item-2" href="/express/index">
			<i class="aui-icon aui-icon-home green f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
		</div>
		<a class="flex-item-2 refresh_btn" href="javascript:location.reload(true);" >
			<i class="aui-icon aui-icon-refresh green f28"></i>
		</a>
	</div>
</header>
<div class="w pt50">
	<div class="fool white clearfix">
		<div class="fl w f12 lh150">
			<p class="f14 mb5 clearfix">
				<span class="fl"> 订单号：<?= $model->order_code ?></span>
				<span class="fr"><?= $model->status->name; ?></span>
			</p>
			<p>下单时间：<?= date('Y-m-d H:i:s',$model->create_at) ?></p>
		</div>
	</div>
	<div class="fool-addr bdb p15 clearfix whitebg mb10">
		<div class="fl w f12 lh150">
			<p class="f14 mb5 clearfix">
				<span class="fr">电话:<?= $model->telephone; ?></span>
				<span class="f14">收货人:<?= $model->contact_name; ?></span>
			</p>

			<p>
				地址：<?= $model->city .'-'.$model->district  .'-'. $model->address; ?></p>

			<p>送货时间：<?= $model->delivery_date ?>  <?= $model->delivery_time ?></p>

			<p>备注：<?= $model->remark ? $model->remark : '无' ?></p>
		</div>
	</div>
	<p class="tit--">商品明细</p>
	<div class="br5 m10 p10 bg-wh">
		<div class="flex-col bdb  lh150 bg-wh">
			<div class="flex-item-2 tc">编号</div>
			<div class="flex-item-8">名称规格</div>
			<div class="flex-item-2 tc">数量</div>
		</div>
		<?php foreach ($model->expressOrderProducts as $key=>$product){?>
			<div class="flex-col p10  lh150 bg-wh">
				<div class="flex-item-2 tc"><?=$key+1?></div>
				<div class="flex-item-8"><?=$product->product_name?$product->product_name:$product->description?></div>
				<div class="flex-item-2 tc">X <?=$product->quantity?></div>
			</div>
		<?php }?>
	</div>


</div>
