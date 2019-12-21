<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/11/5
 * Time: 13:48
 */
use yii\helpers\Html;
$this->title ='每日惠购合伙人福利';
?>
<style>
    .activity-1-coupon::after, .activity-1-coupon::before {
        background-color: #f4f4f4;
    }
</style>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport">
    <div class=" pb50 mb20">
        <div class="br5 bg-wh mb10 activity-1-coupon m10">
            <div class="flex-col bd-d-b">
                <div class="flex-item-7">
                    <h3 class="red pt5 mt1"><?=$model->name?></h3>
                    <span class="gray9"><?=$model->description?$model->description:""?></span>

                </div>
                <div class="flex-item-5 red tr">
                            <span class="f25">￥</span><span class="f40"></span>
                </div>
            </div>

                <div class="f14 pt5">
                    <span class="gray9">截止：<?=date('m-d',strtotime($model->date_start))?>~<?=date('m-d H:i',strtotime($model->date_end))?></span>
                </div>
        </div>
        <?php if($model->details){ ?>
            <?php foreach($model->details as $detail){
                    $product = $detail->product;
                ?>
                <?php if($product->stockCount >0){ ?>
                    <div class="flex-col mb5 br5 whitebg f12 bs coupon-product ml10 mr10" data-id="<?=$product->product_id?>" data-param="<?=$product->getPrice()?>">
                        <div class="flex-item-4 tc pt5 pb5">
                            <a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$product->product_code,'shop_code'=>$product->store_code])?>"><img src="<?=\common\component\image\Image::resize($product->image,100,100)?>" alt="商品图片" width="95" height="95"></a>

                        </div>

                        <div class="flex-item-8 pt10">
                            <a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$product->product_code,'shop_code'=>$product->store_code])?>" class="f14"><?=$product->description->name?> <?php if($product->format){?>[<i class="format fb red"><?=$product->format?></i>]<?php }?></a>
                            <div class="">
                                <div class="num-wrap num2 fr pr10 mt2 numDynamic">
                                    <span class="num-lower iconfont" style="display:none;"></span>
                                    <input type="text" value="0" class="num-text" style="display:none;">
                                    <span class="num-add iconfont"></span>
                                </div>
                                <p>
                                    <span class="gray9">售价 <span class="red f16 mr5 ">￥<?=$product->getPrice()?></span></span>

                                </p>
                                <p>
                                <span class="gray9">单件佣金：<span class="red f16 mr5 ">￥<?=$detail->commissionTotal?></span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
</section>
<?=\h5\widgets\Block\Share::widget();?>
<script>
    <?php
    $this->beginBlock('JS_SKU')
    ?>

    <?php $this->endBlock() ?>
    <?php
    $this->registerJs($this->blocks['JS_SKU'], \yii\web\View::POS_END);
    ?>
</script>
