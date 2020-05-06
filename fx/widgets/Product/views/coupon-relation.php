<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/26
 * Time: 9:44
 */
?>
<?php if($model){?>
<?php foreach($model as $coupon){?>
<dl class="mt5">
	<dt class="p5 whitebg bdt red clearfix"><?=$coupon->comment?$coupon->comment:$coupon->description?> 可用券商品<a href="<?=\yii\helpers\Url::to(['/coupon/view','id'=>$coupon->coupon_id])?>" class="fr red">更多</a> </dt>
	<dd>
		<div class="row">
	<?php
        $i=0;
    foreach($coupon->product as $product){?>
		<?php if(!$product->status) {
				continue;
			}
			$i++;
		    if($i>6){
		        break;
            }
			?>
			<div class="col-3 p5 ">
				<div class="whitebg">
                    <div class="item-photo">
					<a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$product->product->store_code,'product_code'=>$product->product->product_code])?>" class="db">
						<img src="<?=\common\component\image\Image::resize($product->product->image,200,200)?>"  class="w">
					</a>
                        <?php if($product->product->getStockCount()<=0){?> <em class="item-tip iconfont none">&#xe696;</em><?php }?>
                    </div>
					<div class="pt5">
						<a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$product->product->store_code,'product_code'=>$product->product->product_code])?>" class="f14 db lh200 mxh35 tc">
							<?=$product->product->description->name?>
						</a>
						<p class="mt5 mb5 tc clearfix">
							<span class="red vm fl">￥<?=$product->product->getPrice()?></span>
							<a class="redbg db fr white " href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$product->product->store_code,'product_code'=>$product->product->product_code])?>" style="font-size: 25px; height: 25px; width: 25px; line-height: 20px; border-radius: 100%; text-align: center; vertical-align: middle;">+</a>
						</p>
					</div>
				</div>
			</div>
	<?php } ?>
		</div>
	</dd>
</dl>
	<?php } ?>
<?php } ?>
