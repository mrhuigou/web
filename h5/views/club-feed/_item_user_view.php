<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/9
 * Time: 16:36
 */
use \common\component\image\Image;
use \common\component\Helper\Helper;
?>
<!--动态-->
<div class="issuance mt10">
    <div class="pt15 clearfix ">
        <div style="width: 58px;">
            <span class="f24 fb"><?=date('d',strtotime($model->creat_at))?></span><span class="fb"><?=date('m',strtotime($model->creat_at))?>月</span>
        </div>
        <div class="ml5" style="padding-left:58px;margin-top:-23px;">
            <div class="f10 gray6 clearfix">
                <?=Helper::Format_date(strtotime($model->creat_at))?> <?php if($model->type=='try'){ echo '发布了一个试吃评论';}else{ echo '发布了一个活动评论';}?>
                <span class="fr mr10 gray6">
                        <a  href="javascript:;" class="mr10 like-btn iconfont vm f14  " data-type="comment" data-type-id="<?=$model->id?>"><?=$model->likeStatus?"&#xe626;":"&#xe643;"?></a>
                        <a href="javascript:;" class="reply-btn " data-type="comment" data-type-id="<?=$model->id?>">回复</a>
                    </span>
            </div>
            <p class="f12 wordbreak  mt10 mb10"><?=\yii\helpers\Html::encode($model->content)?></p>
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
                            <img class="db pw92 bc pop-show"  data-img="<?=$value?>" src="<?=Image::resize(str_replace(\Yii::$app->params['HTTP_IMAGE'],"",$value),100,100)?>">
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <?php if($model->reply || $model->userLike){ ?>
                <div class="bd br5 bs p10 pr mt10">
                    <!--箭头指向时间啊 ^ -->
                    <div class="arr-bd toparr-bd">
                        <span><i></i></span>
                    </div>
                    <?php if($model->userLike){ ?>
                        <p class="mb10">
                            <i class="iconfont f16 red">&#xe62a;</i>
                            <?php foreach($model->userLike as $user){ ?>
                                <span><?=$user->customer->nickname?></span>,
                            <?php } ?>等觉得很赞！
                        </p>
                    <?php } ?>
                    <?php if($model->reply){ ?>
                        <?php foreach($model->reply as $replay){?>
                            <p class="mb5">
                                <a href="javascript:;" class="reply-btn blue" data-type="comment" data-type-id="<?=$model->id?>" data-content="@<?=$replay->customer?$replay->customer->nickname:"匿名"?> "><?=$replay->customer?$replay->customer->nickname:"匿名"?>：</a><span><?=\yii\helpers\Html::encode($replay->content)?></span><em class="ml5 f12 gray9"><?=Helper::Format_date(strtotime($replay->creat_at))?></em>
                            </p>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
