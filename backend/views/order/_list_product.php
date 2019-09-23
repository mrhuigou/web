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
	<td><?=$model->pay_total?></td>
	<td><?=$model->getRefundQty()?></td>
	<td><?=$model->promotion?'['.$model->promotion->code.']'.$model->promotion->name:'无'?><br/><?=$model->promotionDetail?$model->promotionDetail->promotion_detail_code:''?></td>
	<td><?=$model->gift?'有':'无'?></td>
	<td><?=$model->remark?></td>
</tr>
<?php if($model->gift){
foreach($model->gift as $gift){	?>
<tr>
	<td><?=$gift->product_base_code?></td>
	<td><?=$gift->product_code?></td>
	<td><?=$gift->name?><br/><?=$gift->sku_name?></td>
	<td><?=$gift->unit?></td>
	<td><?=$gift->price?></td>
	<td><?=$gift->quantity?></td>
	<td><?=$gift->total?></td>
	<td><?=$gift->getRefundQty()?></td>
	<td></td>
	<td></td>
	<td>商品赠品</td>
</tr>
<?php } } ?>
