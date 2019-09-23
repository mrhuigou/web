<div class="whitebg pagemain">
    <?php if(isset($model) && $model){?>
<?php $customer_collect = $model;?>
            <?php $store = \api\models\V1\Store::find()->where(['store_code'=>$customer_collect->store_code])->one();?>
            <div class="bdb clearfix"  id="wishlist_<?php echo $customer_collect->customer_collect_id;?>">
                <div class="w240 p5 fl">
                    <p class="mb15">
                        <!--								<input type="checkbox" class="mr10 vm" />-->
                        <a href="javascript:void(0);" val="<?php echo $customer_collect->customer_collect_id;?>" class="del_icon removeBtn vm gray6 mr20">取消收藏</a>
                    </p>
                    <div class="clearfix">
                        <a href="<?php echo \yii\helpers\Url::to(['store/index','shop_code'=>$customer_collect->store_code]);?>" class="fl"><img src="<?php echo \common\component\image\Image::resize($store['image'],120,120) ?>" width="120" height="120" class="mr10"></a>
                        <p class="fl clearfix w140 tl">
                            <a href="<?php echo \yii\helpers\Url::to(['store/index','shop_code'=>$customer_collect->store_code]);?>" class="f14 fb"><?php echo $store->name?></a>
                        </p>
                    </div>
                </div>
                <div class="w700  p5 bdl fr">
                    <p class="mb15"><a href="<?php echo \yii\helpers\Url::to(['store/index','shop_code'=>$customer_collect->store_code]);?>" class="fr mr20">更多商品</a>推荐产品</p>
                    <ul class="shopColl clearfix">
                        <?php if(isset($store->products)){?>
                            <?php foreach ($store->products as $product){?>
                                <li>
                                    <a href="<?php echo \yii\helpers\Url::to(['product/index','shop_code'=>$product->store_code,'product_base_code'=>$product->product_base_code])?>"><img src="<?php echo \common\component\image\Image::resize($product['image'],150,140) ;?>" alt="tu" width="150" height="140" /></a>
                                    <h3><a href="<?php echo \yii\helpers\Url::to(['product/index','shop_code'=>$product->store_code,'product_base_id'=>$product->product_base_id])?>" title="<?php echo $product->description->name;?>"><?php echo $product->description->name;?></a></h3>
                                    <span class="org fb pr10"><?php echo $product->Price;?></span><span class="del"><?php echo $product->sale_price;?></span>
                                </li>
                            <?php }?>
                        <?php }?>
                    </ul>
                </div>
            </div>

    <?php }else{?>

    <?php }?>

</div>