<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/18
 * Time: 20:00
 */
?>
<div class="ts">
<div class="tit1 bluetit1">
	<h2>特色栏目 <a class="fr f12 red mt2" href="<?=\yii\helpers\Url::to(['/read-more/index','subject'=>'PANIC'])?>">更多&gt;&gt;</a></h2>

</div>
<div class="bg-wh pt15" style="padding-left:0.75rem;">
    <div class="t"></div>
    <?php if($ad_1){?>
	<div class="clearfix">
		<?php foreach (array_slice($ad_1,0,1) as $value) { ?>
		<a class="fl" href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" style="margin-right:0.5rem;">
			<img src="<?= \common\component\image\Image::resize($value->source_url, 300, 247) ?>" style="width: 15rem;height: 12.35rem;">
		</a>
		<?php } ?>
		<div class="fl" style="margin-bottom:0.75rem;">
			<?php foreach (array_slice($ad_1,1,1) as $value) { ?>
			<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 15rem;height: 6rem;margin-bottom:0.35rem;"></a>
			<?php } ?>
			<?php foreach (array_slice($ad_1,2,1) as $value) { ?>
			<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 15rem;height: 6rem;"></a>
			<?php } ?>
		</div>
	</div>
   <?php }?>

    <?php if($ad_5){?>
        <div class="clearfix">
            <div class="fl" style="margin-bottom:0.75rem;margin-right:0.5rem;">
                <?php foreach (array_slice($ad_5,0,2) as $key=> $value) { ?>
                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 15rem;height: 6rem;<?php if($key ==0 || $key ==2){ echo 'margin-bottom:0.35rem;';}?>"></a>
                <?php } ?>
            </div>
            <div class="fl" style="margin-bottom:0.75rem;margin-right:0.5rem;">
                <?php foreach (array_slice($ad_5,2,2) as $key=> $value) { ?>
                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 15rem;height: 6rem;<?php if($key ==0 || $key ==2){ echo 'margin-bottom:0.35rem;';}?>"></a>
                <?php } ?>
            </div>
        </div>
    <?php }?>
	<?php if($ad_3){?>
	<div class="clearfix">
		<?php foreach (array_slice($ad_3,0,1) as $value) { ?>
			<a class="fl" href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" style="margin-right:0.5rem;">
				<img src="<?= \common\component\image\Image::resize($value->source_url, 300, 247) ?>" style="width: 15rem;height: 12.35rem;">
			</a>
		<?php } ?>
		<div class="fl" style="margin-bottom:0.75rem;">
			<?php foreach (array_slice($ad_3,1,1) as $value) { ?>
				<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 15rem;height: 6rem;margin-bottom:0.35rem;"></a>
			<?php } ?>
			<?php foreach (array_slice($ad_3,2,1) as $value) { ?>
				<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 15rem;height: 6rem;"></a>
			<?php } ?>
		</div>
	</div>
	<?php }?>
	<?php if($ad_4){?>
	<div class="clearfix">
		<div class="fl" style="margin-bottom:0.75rem;margin-right:0.5rem;">
			<?php foreach (array_slice($ad_4,0,1) as $value) { ?>
				<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 15rem;height: 6rem;margin-bottom:0.35rem;"></a>
			<?php } ?>
			<?php foreach (array_slice($ad_4,1,1) as $value) { ?>
				<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db"><img src="<?= \common\component\image\Image::resize($value->source_url, 300, 120) ?>" style="width: 15rem;height: 6rem;"></a>
			<?php } ?>
		</div>
		<?php foreach (array_slice($ad_4,2,1) as $value) { ?>
			<a class="fl" href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" style="margin-right:0.5rem;">
				<img src="<?= \common\component\image\Image::resize($value->source_url, 300, 247) ?>" style="width: 15rem;height: 12.35rem;">
			</a>
		<?php } ?>
	</div>
	<?php }?>
</div>
</div>
