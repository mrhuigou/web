<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/27
 * Time: 22:31
 */ ?>

<div class="whitebg">
	<a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$model->product->product_code,'shop_code'=>$model->product->store_code])?>" class="db">
		<img src="<?= \common\component\image\Image::resize($model->product->image, 200, 200) ?>" class="w">
	</a>
	<div class="pt5">
		<a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$model->product->product_code,'shop_code'=>$model->product->store_code])?>" class="f14 db lh200 mxh35 tc">
			<?= $model->product->description->name ?>
		</a>
		<p class="mt5 mb5 tc clearfix">
			<span class="red vm fl">￥<?= $model->product->vip_price ?></span>
			<a class="redbg db fr white " href="javascript:;" onclick="AddCart(<?=$model->product->product_base_id?>)"
			   style="font-size: 25px; height: 25px; width: 25px; line-height: 22px; border-radius: 100%; text-align: center; vertical-align: middle;">+</a>
		</p>
	</div>
</div>


