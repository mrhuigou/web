<?php
use yii\helpers\Html;
use \common\component\image\Image;
use \common\component\Helper\Datetime;
?>
<div class="whitebg p10 bdb">
    <div class="pr">
        <div class="pa-lt"><img src="<?=Image::resize($model->customer->photo,100,100)?>" width="40" height="40" alt="ava" class="pop-show"></div>
        <div class="w" style="padding-left: 55px;">
            <span class="blue"><?=$model->customer->nickname?></span>
            <p class="mt2"><?=Html::encode($model->content)?></p>
            <?php if($model->images){
                $images=explode(",",$model->images);
                ?>
                <ul class="clearfix">
                    <?php  foreach($images as $value){ ?>
                        <li class="pw33 fl mt10" >
                            <img class="db pw92 bc pop-show"  data-img="<?=$value?>" src="<?=Image::resize(str_replace(\Yii::$app->params['HTTP_IMAGE'],"",$value),100,100)?>">
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <?php if($model->reply){ ?>
                <div class="bd br5  p10 pr graybg mt5">
                    <?php if($model->reply){ ?>
                        <?php foreach($model->reply as $replay){?>
                            <p class="mb5">
                                <a href="javascript:;" class="reply-btn blue" data-type="comment" data-type-id="<?=$model->id?>" data-content="@<?=$replay->customer?$replay->customer->nickname:"匿名"?> "><?=$replay->customer?$replay->customer->nickname:"匿名"?>：</a><span><?=\yii\helpers\Html::encode($replay->content)?></span><em class="ml5 f12 gray9"><?=Datetime::getTimeAgo($replay->creat_at)?></em>
                            </p>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <div class="pa-rt gray9">
            <?=Datetime::getTimeAgo($model->creat_at)?>
        </div>
    </div>
</div>