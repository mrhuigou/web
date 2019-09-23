<?php
use yii\helpers\Html;
?>
<tr data-key="6">
	<td><?=$model->product_base_code?></td>
	<td><?=$model->product_code?></td>
	<td><?=$model->name?><br/><?=$model->sku_name?></td>
	<td><?=$model->unit?></td>
	<td><?=$model->price?></td>
	<td><?=$model->quantity?></td>
	<td><?=$model->total?></td>
	<td><?=$model->getRefundQty()?></td>
	<td>订单赠品</td>
	<td>
		<?php if($model->promotionOrderDetail){?>
		<?=$model->promotionOrderDetail->promotion_order_title?>[<?=$model->promotionOrderDetail->promotion_code?>] [<?=$model->promotionOrderDetail->promotion_order_code?>]
		<?php }?>
	</td>
</tr>
