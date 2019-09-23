<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/16
 * Time: 23:19
 */ ?>
<?php if ($parent && $parent->image) { ?>
    <h2 class="f14 p5 fb">推荐活动</h2>
    <a href="<?php echo \yii\helpers\Url::to($parent->url)?>">
        <img src="<?=\common\component\image\Image::resize($parent->image)?>" class="w">
    </a>
<?php }?>
	<?php if(!in_array(Yii::$app->request->get('cate_id'),[811,796,799])){?>
		<h2 class="f14 p5 fb">分类</h2>
	<?php } ?>
		<div class="flex-col bg-wh ">
			<?php foreach ($model as $value) { ?>
				<?php if($value->getChild()){ ?>
                    <div class="w ">
					<h2 class="f14 p5 fb bg-f5">
                        <a href="<?= \yii\helpers\Url::to(['search/index', 'cat_id' => $value->category_display_id]) ?>" >
                        <?= $value->description->name ?>
                        </a>
                    </h2>
					<div class="flex-col ">
					<?php foreach($value->getChild() as $val){ ?>
						<div class="flex-item-4 p2">
							<a href="<?= \yii\helpers\Url::to(['search/index', 'cat_id' => $val->category_display_id]) ?>">
								<img src="<?= \common\component\image\Image::resize($val->image, 100, 100) ?>" alt="<?= $val->description->name ?>" class="db w">
							</a>
							<p class="tc lh200 "><?= $val->description->name ?></p>
						</div>
					<?php } ?>
					</div>
					</div>
					<?php }else{ ?>
			<div class="flex-item-4 p2">
				<a href="<?= \yii\helpers\Url::to(['search/index', 'cat_id' => $value->category_display_id]) ?>">
					<img src="<?= \common\component\image\Image::resize($value->image, 200, 200) ?>" alt="<?= $value->description->name ?>" class="db w">
				</a>
				<p class="tc lh200 "><?= $value->description->name ?></p>
			</div>
					<?php } ?>
			<?php } ?>
	</div>
<?php if ($parent) { ?>
	<?php if ($parent->brand) { ?>
		<h2 class="f14 p5 fb mt10">推荐品牌</h2>
		<div class="flex-col bg-wh">
			<?php foreach ($parent->brand as $brand) { ?>
				<div class="flex-item-4 p5">
					<a href="<?= \yii\helpers\Url::to(['search/index', 'brand_id' => $brand->brand_id]) ?>">
						<img src="<?= \common\component\image\Image::resize($brand->image, 200, 100) ?>"  class="db w">
					</a>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
<?php } ?>
