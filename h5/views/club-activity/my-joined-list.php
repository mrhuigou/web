<div class="w clearfix bdb pt5 pb5 whitebg mt10">
    <span class="fl pl10">订单编号： <?=$model->order_no;?></span>
    <span class="fr">
        <?php if($model->order_status_id == 1){ ?>
            <a class=" sbtn redbtn" href="<?=\yii\helpers\Url::to(['/payment/index','trade_no'=>$model->order_id,'showwxpaytitle'=>1],true)?>" > 支付 </a>
        <?php }else{?>
            <span class="pr10"><?=$model->orderStatus->name;?></span>
        <?php } ?>
    </span>
</div>
<a class="whitebg bdb p10 pt15 pb15 pr db w"  href="<?=\yii\helpers\Url::to(['/club-activity/my-info','order_id'=>$model->order_id])?>">
    <div class="pr50 mr30">
        <p class="mxh35 mb5">
            <?=$model->activity->activity_name;?>
        </p>
        <div class="gray9 ">
            <p class="clearfix">
                <i class="fr mr5 metro-basic bluebg-">
                    <?=$model->activity->total==0?"免费":($model->activity->total==2?"AA制":"收费")?>
                </i>
                <i class="iconfont f14 mt2">&#xe61f;</i>
                <span><?=$model->activity->activity_item_name?></span>
            </p>
            <p class="mt2 clearfix">
                <span class="fr pr5 red">￥<?=$model->activity->total?></span>
                <i class="iconfont f14 mt2">&#xe62b;</i>
                <span><?=$model->activity->quantity?>人</span>
            </p>
        </div>
    </div>
    <div class="pa-rt t15 r10">
        <img src="<?=\common\component\image\Image::resize($model->activity->activity->image,50,50,9);?>" alt="<?=$model->activity->activity_name;?>"  width="75" height="75">
    </div>
</a>
