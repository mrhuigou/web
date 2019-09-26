<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/7
 * Time: 20:55
 */
use \common\component\image\Image;
?>
<div class="graybg clearfix mb10 pr hovercr">
    <a href="<?=\yii\helpers\Url::to(['/club/topic/expinfo','id'=>$model->exp_id])?>"><img src="<?=Image::resize($model->cover_image,190,190)?>" alt="<?=$model->title?>"  width="190" height="190" class="db fl"></a>
    <div style="padding-left: 190px;" class="m10">
        <h2 class="f14 mb10"><?=\yii\helpers\Html::encode($model->title)?></h2>

        <p class="mb5">&nbsp;&nbsp;&nbsp;&nbsp;<?=\common\component\Helper\Helper::truncate_utf8_string(strip_tags(\yii\helpers\Html::decode($model->content)),180)?></p>

    </div>

    <div class="pa-b bd_dashT p5" style="margin-left: 200px;">
        <?=$model->total_click?>人已阅读
        <i class="fr cp iconfont f25 trans">&#xe61d;</i>
        <div class="bd tc p10 w150  pa opc-0 white" id="http://m.mrhuigou.com/" style="right: 0px;bottom: 30px;min-height:139px;display: none ">
            <p class="t">微信扫一扫<br>分享给好友</p>
            <span class="img db p5 w bc whitebg" style="height: 75px;width: 75px;"></span>
        </div>
    </div>
</div>

