<?php
use \common\component\image\Image;
use \common\component\Helper\Datetime;
?>

<div class="whitebg p10 bdb a">
    <div class="w clearfix bdb-d"><div class="fl">订单号：<?=$model->order->order_no?></div> <div class="fr"><?=$model->order->orderStatus->name?></div></div>
    <div class="pr">
        <div class="pa-lt"><img src="<?=Image::resize($model->order->customer->photo,100,100)?>" width="54" height="54" alt="ava" class="pop-show"></div>
        <div class="w" style="padding-left: 65px;">
            <?=$model->order->customer->nickname?><br>
            <?=Datetime::getTimeAgo($model->order->date_added)?> 报名 <br>
            <?=$model->activity_item_name?>
        </div>
    </div>
    <div class="b dn pt10">
        <?php foreach($model->option as $option){ ?>
        <p class="bdt-d p10"><?=$option->activity_option_name?>：<?=$option->activity_option_value?></p>
        <?php } ?>
        <ul class="pt10 row pb10 bdb-d bdt-d blue- tc">
            <li class="col-3 bdr">
                <a href="tel:<?=$model->order->customer->telephone?>" ><i class="iconfont f16">&#xe65e;</i>打电话</a>
            </li>
            <li class="col-3 bdr">
                <a href="sms:<?=$model->order->customer->telephone?>" ><i class="iconfont f16">&#xe649;</i>发短信</a>
            </li>
            <li class="col-3 gray9">
                <a href="javascript:;" data-method="post" data-confirm="您确定拒绝此用户报名？"  ><i class="iconfont f16">&#xe65d;</i>拒绝报名</a>
            </li>
        </ul>
    </div>
</div>
