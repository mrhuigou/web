<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/9
 * Time: 16:36
 */
use \common\component\image\Image;
use \common\component\Helper\Helper;
use \common\component\Helper\Datetime;
?>
<!--动态-->
<div class="issuance">
    <figure class="pt15 clearfix">
        <img src="<?=Image::resize($model->customer->photo,45,45)?>" alt="ava" width="45" height="45" class="ava sava mr10 fl pop-show">
        <figcaption class="ml50 pl5">
            <p class="mb5 f14"><?=$model->customer->nickname?></p>
            <p class="f12 wordbreak"><?=\yii\helpers\Html::encode($model->content)?></p>
        </figcaption>
    </figure>
    <?php if($model->tag){?>
        <div class="clearfix mt10">
            <?php foreach($model->tag as $tag){ ?>
                <span class="labeltag orglabel"><?=$tag->tag->name?></span>
            <?php } ?>
        </div>
    <?php } ?>
    <?php if($model->images){
        $images=explode(",",$model->images);
        ?>
        <ul class="clearfix">
            <?php  foreach($images as $value){ ?>
                <li class="pw33 fl mt10" >
                    <img class="db pw92 bc pop-show"  data-img="<?=$value?>" src="<?=Image::resize(str_replace(\Yii::$app->params['HTTP_IMAGE'],"",$value))?>">
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
    <!--回复-->
    <div class="gray9 mt10 mb10">
    <span class="fr">
        <a  href="javascript:;" class="mr10 like-btn iconfont vm f14  " data-type="comment" data-type-id="<?=$model->id?>"><?=$model->likeStatus?"&#xe626;":"&#xe643;"?></a>
       <a href="javascript:;" class="reply-btn" data-type="comment" data-type-id="<?=$model->id?>">回复</a>
    </span>
        <span class="tahoma"><?=Datetime::getTimeAgo($model->creat_at)?></span>
    </div>
    <?php if($model->reply){ ?>
        <div class=" br5  p10 pr f5bg">
                <?php foreach($model->reply as $replay){?>
                    <p class="mb5">
                        <a href="javascript:;" class="reply-btn blue" data-type="comment" data-type-id="<?=$model->id?>" data-content="@<?=$replay->customer?$replay->customer->nickname:"匿名"?> ">
                            <?=$replay->customer?$replay->customer->nickname:"匿名"?>：
                        </a>
                        <span><?=\yii\helpers\Html::encode($replay->content)?></span>
                        <em class="ml5 f12 gray9">
                            <?=Datetime::getTimeAgo($replay->creat_at)?>
                        </em>
                    </p>
                <?php } ?>
        </div>
    <?php } ?>
</div>
