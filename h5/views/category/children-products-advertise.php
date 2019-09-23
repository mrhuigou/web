<?php if($products){?>
    <?php foreach ($products as $product){?>
        <?php if($product->beintoinv && $product->stockCount>0){?>
            <div class="flex-col bg-wh mb5 p5">
                <a href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$product->product_base_code,'shop_code'=>$product->store_code])?>" >
                    <div class="flex-item-4 pr2">
                        <img src="<?=\common\component\image\Image::resize($product->image)?>" alt="" class="w">
                    </div>
                </a>
                <div class="flex-item-8 pl5">
                    <a href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$product->product_base_code,'shop_code'=>$product->store_code])?>" class="row-two f13 mb10"> <?=$product->description->name;?></a>

                    <span class="btn-circle-add iconfont fr addCart" onclick="AddCart(<?=$product->product_base_id?>)"></span>
                    <span class="red f16 mr5">￥<?=$product->getPrice()?> </span><span class="gray9 del">￥<?=$product->price?></span>
                </div>

            </div>
        <?php } ?>
    <?php }?>

    <?php foreach ($products as $product){?>
        <?php if($product->beintoinv &&  $product->stockCount <= 0){?>
            <div class="flex-col bg-wh mb5 p5">
                <a href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$product->product_base_code,'shop_code'=>$product->store_code])?>" >
                    <div class="flex-item-4 pr2">
                        <img src="<?=\common\component\image\Image::resize($product->image)?>" alt="" class="w">
                    </div>
                </a>
                <div class="flex-item-8 pl5">
                    <a href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$product->product_base_code,'shop_code'=>$product->store_code])?>" class="row-two f13 mb10"> <?=$product->description->name;?></a>
                    <button class="appbtn graybtn pl10 pr10 mr5">已售罄</button>
                </div>

            </div>
        <?php } ?>
    <?php }?>
<?php }else{?>
    暂无商品
<?php }?>