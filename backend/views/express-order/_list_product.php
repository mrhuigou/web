<?php
use yii\helpers\Html;
?>
<tr data-key="6">
    <td><?=$model->product_base_code ? $model->product_base_code : "未设置"?></td>
    <td><?=$model->product_code ? $model->product_code:"未设置" ?></td>
    <td><?=$model->product_name?><br/><?php echo $model->product? $model->product->sku_name:'';?></td>
    <td><?php echo $model->product? $model->product->unit:'';?></td>
<!--    <td>--><?php //echo $model->price?><!--</td>-->
    <td><?=$model->quantity?></td>
<!--    <td>--><?php //$model->total?><!--</td>-->
<!--    <td>--><?php //echo $model->pay_total?><!--</td>-->
<!--    <td>--><?php //echo $model->getRefundQty()?><!--</td>-->
    <td><?=$model->description?></td>
</tr>

