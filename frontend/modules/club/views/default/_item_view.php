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
    <a href="<?=\yii\helpers\Url::to(['/club/default/info','id'=>$model->id])?>"><img src="<?=Image::resize($model->image,190,190)?>" alt="<?=$model->title?>"  width="190" height="190" class="db fl"></a>
    <div style="padding-left: 190px;" class="m10">
        <h2 class="f14 mb10"><?=\yii\helpers\Html::encode($model->title)?></h2>
        <p class="mb5"><i class="iconfont">&#xe61b;</i><?=date('m月d日 H:i',strtotime($model->begin_datetime))?>  <?=\common\component\Helper\Datetime::getWeekDay(strtotime($model->begin_datetime))?>  开始  &nbsp;&nbsp;<span class="gray9">(08月27日 18:30 报名截止)</span></p>
        <p class="mb5"><i class="iconfont">&#xe61a;</i><?=$model->address?$model->address:'暂未确定'?></p>
        <p class="mb5"><i class="iconfont">&#xe61c;</i> 已报 <b class="red"><?=$model->tickets?></b> 名额 （<?php if($model->qty) { ?>限 <b class="red"><?=$model->qty?></b> 名额<?php }else{ ?>不限 名额<?php } ?>）</p>
    </div>

    <div class="pa-b bd_dashT p5" style="margin-left: 200px;">
        <?=$model->click_count?>人已阅读
        <i class="fr cp iconfont f25 trans">&#xe61d;</i>
        <div class="bd tc p10 w150  pa opc-0 white" id="http//m.mrhuigou.com/club-activity/detail?id=<?=$model->id?>" style="right: 0px;bottom: 30px;min-height:139px;display: none ">
            <p class="t">微信扫一扫<br>分享给好友</p>
            <span class="img db p5 w bc whitebg" style="height: 75px;width: 75px;"></span>
        </div>
    </div>
</div>

