<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/9/29
 * Time: 10:18
 */
use \common\component\image\Image;
use \common\component\Helper\Datetime;
?>
<?php if($model){ ?>
        <?php foreach($model as $value){ ?>
            <div class="clearfix  col-6">
                <div class="p10">
                    <img src="<?=Image::resize($value->customer->photo,45,45)?>" alt="ava" width="45" height="45" class="ava sava mr10 fl">
                    <div class="ml50 pl5">
                        <a href="javascript:;" class="mb5 gray6 oh mxh20 db w"><?=\yii\helpers\Html::encode($value->customer->nickname)?></a>
                        <p class="gray9"><?=Datetime::getTimeAgo($value->creat_at)?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?= \common\extensions\widgets\more\MorePager::widget(['id'=>'TryUser','pagination' => $pages,'options'=>['class'=>'pagination  w graybg lh250'],]); ?>
<?php }else{?>
    <div class="p10 lh200 graybg tc ">Hi～还没有人报名</div>
<?php }?>
