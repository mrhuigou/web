<?php
use \common\component\image\Image;
use \common\component\Helper\Helper;
?>
<div class="clearfix pt10 pb10 bdb-d">
    <div class="ava sava fl"><img src="<?=Image::resize($model->customer->photo,100,100)?>" alt="ava" class="w pop-show"></div>
    <p class=" ml10 fl"><?=$model->customer->nickname?$model->customer->nickname:"匿名"?>
    <?php if($model->status==1){ ?><br/>
            <i class="red fb">成功获得试吃机会</i>
        <?php } ?>
    </p>
    <span class="fr mt10"><?=date('Y-m-d',strtotime($model->creat_at))?></span>
</div>
