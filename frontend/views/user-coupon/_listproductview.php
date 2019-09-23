<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/9
 * Time: 19:53
 */?>

	<div class="p5">
		<div class="bd whitebg">
			<a href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$model->product->product_code,'shop_code'=>$model->product->store_code])?>" class="db">
				<img class="w db"  src="<?=\common\component\image\Image::resize($model->product->image,190,190)?>"">
			</a>
			<div class="p10">
				<span class="red f16">￥<?=$model->product->getPrice()?></span>
				<a class="mxh40 db" href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$model->product->product_code,'shop_code'=>$model->product->store_code])?>">
					<?=\yii\helpers\Html::encode($model->product->description->name)?>
				</a>
			</div>
		</div>
	</div>