<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/9
 * Time: 19:53
 */?>

<div class="p5">
	<div class="bd whitebg clearfix pr">
		<div class="pw35 fl">
			<img class="db w" src="<?=\common\component\image\Image::resize($model->coupon->image_url,300,300)?>">
		</div>
		<div class="pw45 fl">
			<div class="p10 lh pt25">
				<div class="lh200 pl10">
					<p class="mxh20"><?=$model->coupon->name?></p>
					<p class="mxh20"><?=$model->coupon->comment?$model->coupon->comment:$model->coupon->description?></p>
					<p><?=date('m/d',strtotime($model->start_time))?> ~  <?=date('m/d',strtotime($model->end_time))?></p>
				</div>
			</div>
		</div>
		<div class="pw20 fr graybg2 tc pa-rt b0 pt40">
			<p class="f14 red pb5 fb"><?php if ($model->coupon->type=='F'){ ?>
					￥<?=number_format($model->coupon->discount,2,'.','')?>
				<?php }else{ ?>
					<?=$model->coupon->getRealDiscount()?>折
				<?php }?></p>
			<?php if($model->coupon->product){?>
			<a href="<?=\yii\helpers\Url::to(['/user-coupon/detail','id'=>$model->customer_coupon_id])?>">去使用</a>
			<?php }?>
		</div>
	</div>
</div>
