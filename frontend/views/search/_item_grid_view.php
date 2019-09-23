<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/9/2
 * Time: 14:09
 */
use yii\helpers\Html;
?>

		<dl class="item <?=$index%5==4?"last":""?>" >
			<dt class="photo">
				<a href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$model->productBase->product_base_code,'shop_code'=>$model->productBase->store_code])?>" target="_blank">
					<img class="lazy" src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($model->productBase->defaultImage,500,500)?>" />
				</a>
			</dt>
			<?php if($model->productBase->product){ ?>
				<dd class="thumb">
					<!--包装-->
					<div class="picScroll-left-list example1">
						<div class="hd clearfix">
							<a class="next">></a>
							<a class="prev"><</a>
						</div>
						<div class="bd">
							<ul class="picList clearfix">
								<?php foreach($model->productBase->product as $key=>$sub){ ?>
									<li>
										<div class="pic">
											<a href="javascript:;" data-sku="<?=$sub->sku?>" for="<?=$sub->getPrice()?>">
												<img class="lazy" src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($sub->image,500,500)?>" />
											</a>
										</div>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</dd>
			<?php } ?>
			<dd class="detail">
				<a class="item-name"  href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$model->productBase->product_base_code,'shop_code'=>$model->productBase->store_code])?>" target="_blank" >
					<?=isset($model->_highlight['item_name'])?current($model->_highlight['item_name']):Html::encode($model->productBase->description->name);?>
				</a>
				<div class="attribute">
					<div class="cprice-area"><span class="symbol">¥</span><span class="c-price"><?=$model->productBase->price?></span></div>
				</div>
			</dd>
		</dl>
