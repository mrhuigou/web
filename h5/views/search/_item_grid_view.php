<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/1/19
 * Time: 14:09
 */
use yii\helpers\Html;
use yii\helpers\Url;
use \common\component\image\Image;
?>
    <div class="clearfix">
        <a href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$model->productBase->product_base_code,'shop_code'=>$model->productBase->store_code])?>">
            <div class="p5">
                <h2 class="f14 gray9 lh200"><?=isset($model->_highlight['item_name'])?current($model->_highlight['item_name']):Html::encode($model->productBase->description->name);?></h2>
                <p class="red"><?=Html::encode($model->productBase->description->meta_keyword)?></p>
                <?php if($model->productBase->bedisplaylife){?>
                    <p >
                        <span class="p2 bd-green green lh150 f12">保质期 :<?=$model->productBase->life?></span>
                        <?php if($model->productBase->productDate){?><span class="p2 greenbg white lh150 f12">生产日期：<?=$model->productBase->productDate?></span><?php }?>
                    </p>
                <?php } ?>
            </div>
            <div class="row pl5">
                <?php if($model->productBase->imagelist){ ?>
                    <?php foreach(array_slice($model->productBase->imagelist,0,3) as $key=>$image){ ?>
                        <div class="col-3 pr5">
                            <img data-original="<?=\common\component\image\Image::resize($image,190,190)?>" alt="<?=$model->productBase->description->name?>" class="lazy db w fl mr15" >
                        </div>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="col-3 pr5">
                        <img data-original="<?=\common\component\image\Image::resize($model->productBase->defaultImage,190,190)?>" alt="<?=$model->productBase->description->name?>" class="lazy db w fl mr15" >
                    </div>
                <?php } ?>
            </div>
        </a>
        <div class="p5 clearfix">
            <p class="fl pt5">
                <span class="red f14  ">￥<?=$model->productBase->price?></span><span class="del pl10 gray9">￥<?=$model->productBase->sale_price?></span>
            </p>
            <?php if($model->productBase->online_status){?>
            <?php if($model->productBase->stockCount>0){?>
            <a class="fr btn mbtn redbtn" href="javascript:;" onclick="AddCart(<?=$model->productBase->product_base_id?>)">立即购买</a>
            <?php }else{ ?>
                <span class="fr btn mbtn graybtn ">已售罄</span>
            <?php } ?>
            <?php }else{ ?>
                <span class="fr btn mbtn graybtn ">已下架</span>
            <?php } ?>
        </div>
    </div>
