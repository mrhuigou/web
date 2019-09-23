<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/4
 * Time: 11:59
 */
use \yii\helpers\Html;

?>

<div class="flex-col bd m5 clearfix">
	<a class="flex-item-4" href="<?=\yii\helpers\Url::to(['shop/index','shop_code'=>$model->store->store_code])?>">
		<img src="<?=\common\component\image\Image::resize($model->store->logo,100,100)?>" class="w">
	</a>
	<a class="flex-item-6 flex-middle flex-row flex-center fb" href="<?=\yii\helpers\Url::to(['shop/index','shop_code'=>$model->store->store_code])?>">
			<?=Html::encode($model->store->name)?>
	</a>
	<div class="flex-item-2 flex-middle flex-row flex-center  bluebg">
		<a class="white" href="<?=\yii\helpers\Url::to(['/collect/cancel','id'=>$model->customer_collect_id])?>">取消收藏</a>
	</div>
</div>
