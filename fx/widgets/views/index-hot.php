<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/18
 * Time: 20:00
 */
?>
<div class="ts">
<div class="tit1 bluetit1" style="display: none">
	<h2>特色栏目
<!--        <a class="fr f12 red mt2" href="--><?//=\yii\helpers\Url::to(['/read-more/index','subject'=>'PANIC'])?><!--">更多&gt;&gt;</a></h2>-->

</div>
<div class="bg-wh pt15" style="padding-left:0.75rem;">
    <div class="t"></div>
    <?php if($ad_1){?>
        <div class="clearfix">
            <div class="fl" style="margin-bottom:0.75rem;margin-right:0.5rem;">
                <?php foreach (array_slice($ad_1,0,1) as $key=> $value) { ?>
                    <a href="<?= \yii\helpers\Url::to(['affiliate-plan-detail/index','plan_id'=>$value->affiliate_plan_id]) ?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 14rem;height: 6rem;<?php if($key ==0 || $key ==2){ echo 'margin-bottom:0.35rem;';}?>"></a>
                <?php } ?>
            </div>
            <div class="fl" style="margin-bottom:0.75rem;margin-right:0.5rem;">
                <?php foreach (array_slice($ad_1,1,1) as $key=> $value) { ?>
                    <a href="<?= \yii\helpers\Url::to(['affiliate-plan-detail/index','plan_id'=>$value->affiliate_plan_id])?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 14rem;height: 6rem;<?php if($key ==0 || $key ==2){ echo 'margin-bottom:0.35rem;';}?>"></a>
                <?php } ?>
            </div>
        </div>
    <?php }?>
</div>
</div>
