<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/15
 * Time: 11:24
 */
?>
<a href="<?=\yii\helpers\Url::to(['/express/order-info','order_no'=>$model->order_code])?>">
<div class="br5 m10 p10 bg-wh">
	<h2 class="clearfix"><span class="fl">订单号：<?=$model->order_code?></span><span class="fr org"><?=$model->status?$model->status->name:'提交成功'?></span></h2>
	<div class="flex-col bdb  lh150 bg-wh">
		<div class="flex-item-2 tc">编号</div>
		<div class="flex-item-8">名称规格</div>
		<div class="flex-item-2 tc">数量</div>
	</div>
	<?php foreach ($model->expressOrderProducts as $key=>$product){?>
		<div class="flex-col  p10 lh150 bg-wh">
			<div class="flex-item-2 tc"><?=$key+1?></div>
			<div class="flex-item-8"><?=$product->product_name?$product->product_name:$product->description?></div>
			<div class="flex-item-2 tc">X <?=$product->quantity?></div>
		</div>
	<?php }?>
</div>
</a>
