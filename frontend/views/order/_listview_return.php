<?php
use yii\helpers\Html;
?>
<table class="bdt bdl bdr w mt10 whitebg">
	<colgroup>
		<col width="50%" />
		<col width="10%"  />
		<col width="10%"  />
		<col width="15%"  />
		<col width="15%"  />
	</colgroup>
	<tr>
		<th colspan="5" class="tl p5 graybg">
			<span class=" ml5 vm">
				退单编号：<?= $model->return_code; ?> &nbsp;&nbsp;&nbsp;&nbsp; 申请时间：<?= $model->date_added; ?>
			</span>
		</th>
	</tr>
	<tr>
		<td colspan="3">
			<?php if($model->returnProduct){ ?>
				<?php foreach ($model->returnProduct as $op) { ?>
					<table class="bdb p10 w">
						<colgroup>
							<col width="50%"/>
							<col width="10%"/>
							<col width="10%"/>
						</colgroup>
						<tr>
							<td>
								<div class="p5 fl">
									<img src="<?=\common\component\image\Image::resize($op->product?$op->product->image:'',50,50)?>"  class="bd " width="50">
								</div>
								<div class="p5">
									<h2><?= $op->name; ?></h2>
									<p><?=$op->sku_name?></p>
								</div>
							</td>
							<td class="bdl bdb tc"> <?=$op['quantity']; ?></td>
							<td class="bdl bdb tc">
								<p><?=$op['total']; ?></p>
							</td>
						</tr>
					</table>
				<?php } ?>
			<?php } ?>
		</td>
		<td class="bdl bdb tc fb"><?=number_format($model->total, 2); ?></td>
		<td class="bdl bdb tc">
			<?=$model->returnStatus->name?>
		</td>
	</tr>
	<tr>
		<td colspan="5" class="bdl bdb tl p5">
			<?=$model->comment?>
		</td>
	</tr>
</table>
