<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/16
 * Time: 14:13
 */
?>
<?php if ($models) { ?>
		<dl class="mb10" >
			<dt>同类品牌商品：</dt>
			<dd class="clearfix  graybg" style="max-height: 170px;overflow: auto;">
				<?php foreach($models as $item){
					if(!$item->getOnline_status()){
						continue;
					}
					?>
					<div class="bd p5 m5 nowrap vm fl"><img src="<?=\common\component\image\Image::resize($item->defaultImage, 15, 15)?>" width="15" class="p5 bd vm"> <a   href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$item->product_base_code,'shop_code'=>$item->store_code])?>"> <?=$item->description->name?></a></div>
				<?php } ?>
			</dd>
		</dl>
<?php } ?>
