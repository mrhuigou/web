<?php
use \common\component\image\Image;
use \common\component\Helper\Datetime;
?>
<?php if($model->order->customer){ ?>
<div class="clearfix pt10 pb10 bdb-d">
    <div class="ava sava fl"><img src="<?=Image::resize($model->order->customer->photo,100,100)?>" alt="ava" class="w pop-show"></div>
    <p class="tc mt10 ml10 fl"><?=$model->order->customer->nickname?></p>
    <span class="fr mt10"><?=Datetime::getTimeAgo($model->order->date_added)?></span>
</div>
<?php } ?>
