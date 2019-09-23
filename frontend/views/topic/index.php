<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/9
 * Time: 19:16
 */
$this->title =$model->name;
?>
<div id="content">
<?php if ($model) { ?>
	<?php if ($model->image) { ?>
		<img src="<?= \common\component\image\Image::resize($model->image, 1100, 285,9) ?>" alt="" class="db w">
	<?php } ?>
<?php } ?>
<div class="w1100 bc">
	<!--列表-->
	<?php if ($details) { ?>
	<div class="clearfix lr-m5">
		<?php foreach ($details as $promotion) { ?>
		<div class="brands-list">
			<a href="<?=\yii\helpers\Url::to(['/topic/detail','code'=>$promotion->code])?>" class="db"><img src="<?= \common\component\image\Image::resize($promotion->image_url, 545, 226) ?>" width="545" height="226"></a>
			<div class="p lh200">
				<p class="f14"><?= $promotion->name ?></p>
				<p class="f14 gray6"><?= $promotion->description ?></p>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>
</div>