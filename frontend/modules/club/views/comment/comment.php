<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/10/9
 * Time: 15:46
 */
use yii\widgets\LinkPager;
use \common\component\image\Image;
use \common\component\Helper\Datetime;
?>
<?php if($model){
    ?>
<?php foreach($model as $value){ ?>
<div class="issuance data_item">
    <div class="pt15 clearfix">
        <img width="45" height="45" class="ava sava mr10 fl" alt="ava" src="<?=Image::resize($value->customer->photo,45,45)?>">
        <div class="ml50 pl5">
            <p class="mb5 f14"></p>
            <p class="f12 wordbreak"><?=\yii\helpers\Html::encode($value->content)?></p>
        </div>
    </div>
        <?php if($value->images){
            $images=explode(",",$value->images);
            ?>
    <ul class="clearfix">
            <?php  foreach($images as $val){ ?>
        <li class="pw33 fl mt10">
            <img class="db pw92 bc" alt="tu" src="<?=Image::resize(str_replace(\Yii::$app->params['HTTP_IMAGE'],"",$val),264,264)?>">
        </li>
            <?php } ?>
    </ul>
        <?php } ?>
    <!--回复-->
    <div class="gray9 mt10 mb10">
							    <span class="fr">
							       <a  href="javascript:;" class="mr10 like-btn iconfont vm f14  " data-type="comment" data-type-id="<?=$value->id?>"><?=$value->likeStatus?"&#xe61f;":"&#xe61e;"?></a>
       回复(<?=count($value->reply)?>+)
							    </span>
        <span class="tahoma"><?=Datetime::getTimeAgo($value->creat_at)?></span>
    </div>


    <div class=" br5  p10 pr f5bg replay_content">
        <!--箭头指向时间啊 ^ -->
        <div class="arr-bd toparr-bd">
            <span></span>
        </div>
        <?php if($value->reply){?>
            <?php foreach($value->reply as $replay){ ?>
        <p class="mb5">
            <a href="javascript:;" class="reply-btn blue" data-type="comment" data-type-id="<?=$value->id?>" data-content="@<?=$replay->customer?$replay->customer->nickname:"匿名"?> ">
                <?=$replay->customer?$replay->customer->nickname:"匿名"?>：
            </a>
            <span><?=\yii\helpers\Html::encode($replay->content)?></span>
            <em class="ml5 f12 gray9"><?=Datetime::getTimeAgo($replay->creat_at)?></em>
        </p>
            <?php } ?>
        <?php } ?>
        <form class="comment_form">
            <p class="mb5 clearfix">
                <input name="type" type="hidden" value="comment">
                <input name="type_id" type="hidden" value="<?=$value->id?>">
                <input name="content" placeholder="回复评论" class=" fl w700 input minput" value="" maxlength="85">
                <a class="btn btn_middle grayBtn fl w70 comment_submit" href="javascript:;">回复评论</a>
            </p>
        </form>
    </div>

</div>
        <?php } ?>
    <?=\common\extensions\widgets\more\MorePager::widget(['id'=>'comment_content','pagination' => $pages,'options'=>['class'=>'pagination  w graybg lh250'],]); ?>
<?php }else{ ?>
    <div class="p10 lh200 graybg tc ">Hi～还没有人留言</div>
<?php } ?>
