<?php
use \common\component\image\Image;
use \common\component\Helper\Datetime;
?>
<div class="whitebg p10 bdb">
    <div class="pr">
        <div class="pa-lt"><img src="<?=Image::resize($model->customer->photo,100,100)?>" width="40" height="40" alt="ava" class="pop-show"></div>
        <div class="w pt10 pb10" style="padding-left: 55px;">
            <?=$model->customer->nickname?$model->customer->nickname:"陌生人"?>
        </div>
        <div class="pa-rt pt10 gray9">
            <?=Datetime::getTimeAgo($model->creat_at)?>
        </div>
    </div>
</div>