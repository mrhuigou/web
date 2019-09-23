<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/7
 * Time: 20:55
 */
use \common\component\image\Image;
?>
<a class="whitebg bdb p10 pt15 pb15 pr db"  href="<?=\yii\helpers\Url::to(['/club-activity/detail','id'=>$model->id])?>">
    <div class="pr50 mr30">
        <p class="mxh35 mb5">
           <?=$model->title?>
        </p>
        <div class="mxh35 gray9">
            <p>
                <i class="fr mr5 metro-basic bluebg-">
                    <?=$model->fee==0?"免费":($model->fee==2?"AA制":"收费")?>
                </i>
                <i class="iconfont f14 mt2">&#xe660;</i>
                <span><?=date('m月d日 H:i',strtotime($model->begin_datetime))?>( <?=\common\component\Helper\Datetime::getWeekDay(strtotime($model->begin_datetime))?> )</span>
            </p>
            <p class="mt2">
                <span class="fr pr5"> <?php if($model->qty) { ?>限 <i class="red"><?=$model->qty?></i> 名额<?php }else{ ?>不限 名额<?php } ?></span>
                <i class="iconfont f14 mt2">&#xe65f;</i>
                <span><?=$model->address?$model->address:'暂未确定'?></span>
            </p>
        </div>
    </div>
    <div class="pa-rt t15 r10">
        <img data-original="<?=Image::resize($model->image,75,75)?>" alt="<?=$model->title?>" class="lazy"  width="75" height="75">
    </div>
</a>