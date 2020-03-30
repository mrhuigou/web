<div class="order_item mb10">
	<h2 class="p10  bdb whitebg"><span class="vm"><?=$model->getReturnType()?>编号：<?= $model->return_code; ?></span> <span
			class="vm fr org"> <?= $model->returnStatus->name; ?> </span></h2>
	<?php foreach ($model->returnProduct as $return_proudct) { ?>
        <table class="bg-wh tbp5 w" >
            <tr >
                <td width="30%">
	                <?php if ($return_proudct->product) { ?>
                        <img
                                src="<?= \common\component\image\Image::resize($return_proudct->product ? $return_proudct->product->image : '', 100, 100) ?>"
                                class="bd w">
	                <?php } ?>
                </td>
                <td valign="top">
                    <h2 class="row-two"><?= $return_proudct->name; ?></h2>
                    <p class="gray9  mt2"><?= $return_proudct->sku_name; ?></p>
                </td>
                <td width="20%" class="tc">
                    <p class="gray6">x <em class="qty"><?= $return_proudct->quantity; ?></em></p>
                    <p>￥<?= number_format($return_proudct->total, 2, '.', '') ?></p>
                </td>
            </tr>
        </table>
	<?php } ?>
	<div class="p10 tl bdt  bg-wh">
		<p class="f12"> <label>售后原因：</label><?= $model->comment ?> </p>
	</div>
	<div class="p10 tr bdb  whitebg">
		<span>合计：￥<?= number_format($model->total, 2); ?> </span>
	</div>
</div>