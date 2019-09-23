<ul class="pro-list2 lists" id="list">
    <?php foreach ($models as $model){?>
        <?php if($model && $model['productBase']){?>
<li class="clearfix">
            <a data-content="<?php echo $page?>" data-filter="<?php echo \yii\helpers\Url::to(['/product/index',"product_code"=>$model['productBase']->product_base_code,'shop_code'=>$model['productBase']->store_code])?>" href="javascript:void(0)" class="db w-per30 fl urlgoto">
                <img data-original="<?=\common\component\image\Image::resize($model['productBase']->defaultImage,180,180)?>" alt="<?=$model['productBase']->description->name?>" class="lazy db w fl mr15" >
            </a>
            <div class="w-per65 fr">
                <a data-content="<?php echo $page?>" href="javascript:void(0)" data-filter="<?php echo \yii\helpers\Url::to(['/product/index',"product_code"=>$model['productBase']->product_base_code,'shop_code'=>$model['productBase']->store_code])?>" class="db pt10 pb5 urlgoto" >
                    <?=$model['product_name'];?>
                </a>
                 <p class="red "><?=$model['productBase']->description->meta_description?></p>
                <p class="red fb">￥<?=$model['productBase']->price?> <span class="del pl10 gray9">￥<?=$model['productBase']->sale_price?></span></p>
                <p class="mt10 mb10">
                    <?php if($model['productBase']->online_status){?>
                    <?php if($model['productBase']->stockCount>0){?>
                    <button class="appbtn redbtn pl10 pr10 mr5" onclick="AddCart(<?=$model['productBase']->product_base_id?>)">立即购买</button>
                    <?php }else{ ?>
                    <button class="appbtn graybtn pl10 pr10 mr5">已售罄</button>
                    <?php } ?>
                    <?php }else{ ?>
                        <button class="appbtn graybtn pl10 pr10 mr5">已经下架</button>
                    <?php } ?>
                </p>
            </div>
</li>
        <?php }?>
    <?php }?>
</ul>