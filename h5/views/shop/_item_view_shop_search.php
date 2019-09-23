<li class="clearfix">
            <a href="<?=\yii\helpers\Url::to(['/product/index',"product_base_id"=>$model->product_base_id])?>" class="db w-per30 fl">
                <img src="<?=\common\component\image\Image::resize($model->image,180,180)?>" alt="tu" class="db w fl mr15" >
            </a>
            <div class="w-per65 fr">
                <a href="<?=\yii\helpers\Url::to(['/product/index',"product_base_id"=>$model->product_base_id])?>" class="db pt10 pb5" ><?=$model->description->name?></a>
                <p class="red "><?=$model->description->meta_description?></p>
                <p class="red fb">￥<?=$model->price?> <span class="del pl10 gray9">￥<?=$model->sale_price?></span></p>
                <p class="mt10 mb10">
                    <?php if($model->online_status){?>
                        <?php if($model->stockCount>0){?>
                            <button class="appbtn redbtn pl10 pr10 mr5" onclick="AddCart(<?=$model->product_base_id?>)">立即购买</button>
                        <?php }else{ ?>
                            <button class="appbtn graybtn pl10 pr10 mr5">卖光了</button>
                        <?php } ?>
                    <?php }else{ ?>
                        <button class="appbtn graybtn pl10 pr10 mr5">已经下架</button>
                    <?php } ?>
                </p>
            </div>
</li>
