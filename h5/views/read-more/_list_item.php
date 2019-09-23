<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/3/29
 * Time: 11:33
 */
?>
<?php foreach ($models as $model) { ?>
	<?php if ($model->type == 'news') { ?>
        <div class="item bg-wh mb5" id="item_postion_<?=$model->news_id?>">
            <div class="item-padding">
                <a href="<?= $model->link_url ? \yii\helpers\Url::to($model->link_url) : \yii\helpers\Url::to(['news/index', 'news_id' => $model->news_id]) ?>">
                    <div class="item-inner">
                        <div class="item-photo" style="float: right;">
                            <img src="<?= \common\component\image\Image::resize($model->image, 200, 200) ?>"
                                 class="db w">
                        </div>
                        <div class="item-detail" style="float: left;">
                            <h2 class="item-name mt10  h ellipsis">
								<?php if ($model->tag) { ?>
                                    <span class="btn btn-xxs btn-bd-red"><?= trim($model->tag) ?></span>
								<?php } ?>
								<?= $model->title ?>
                            </h2>
                            <div class="f12"
                                 style="height: 2.6em;color: #666666;"><?= $model->meta_description ?></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
	<?php } else { ?>
        <div class="item bg-wh mb5" id="item_postion_<?=$model->news_id?>">
            <div class="item-padding">
                <a href="<?= $model->link_url ? \yii\helpers\Url::to($model->link_url) : \yii\helpers\Url::to(['news/index', 'news_id' => $model->news_id]) ?>">
                    <div class="item-inner p10">
                        <h2 class="f12 row-two-max mb10">
							<?php if ($model->tag) { ?>
                                <span class="btn btn-xxs btn-bd-red"><?= trim($model->tag) ?></span>
							<?php } ?>
							<?= $model->title ?>
                        </h2>
                        <img src="<?= \common\component\image\Image::resize($model->image, 640, 266) ?>" class="w">
                    </div>
                </a>
            </div>
        </div>
	<?php } ?>
<?php } ?>
