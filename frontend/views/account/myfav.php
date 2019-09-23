<?php 
use yii\helpers\Html;
if(!count($model)){
	echo '<h4 class="tc">暂无推荐商品</h4>';
	return false;
}
foreach ($model as $value) {
 ?>
<li>
	<a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->productbase->store_code,'product_base_code'=>$value->productbase->product_base_code],true)?>">
		<img src="<?=\common\component\image\Image::resize($value->productbase->image,205,205) ?>" alt="tu" class="db w">
	</a>
	<div class="p10">
		<p class="tahoma"><span class="f16 fb org">¥<?=$value->productbase->getPrice();?></span>  <span class="gray6 del">¥<?=$value->productbase->price;?></span></p>
		<a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->productbase->store_code,'product_base_code'=>$value->productbase->product_base_code],true)?>" class="db mxh20 mt2">
			<?=$value->productbase->description->name?>
		</a>
		<p class="gray9 mt2">
			<span class="fr">库存: <?=$value->productbase->StockCount;?></span>
			<span>好评：100%</span>
		</p>
	</div>
</li>
<?php } ?>