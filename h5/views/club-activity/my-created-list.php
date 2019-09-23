<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/7
 * Time: 20:55
 */
use \common\component\image\Image;
?>
<a class="whitebg bdb db mt10"  href="<?=\yii\helpers\Url::to(['/club-activity/manage','id'=>$model->id])?>">
    <div class="p10 pt15 pb15 pr">
        <div class="pr50 mr30">
            <div class="pr lh44">
                <span class="dib  ml5 mxh12"><?=$model->title;?></span>
            </div>
            <p class="gray9 mxh20">
                <i class=" mr5 metro-basic bluebg-">
                    <?=$model->fee==0?"免费":($model->fee==2?"AA制":"收费")?>
                </i>
                <i class="iconfont f14 mt2">&#xe660;</i>
                <span><?=date('m/d H:i',strtotime($model->begin_datetime))?> - <?=date('m/d H:i',strtotime($model->end_datetime))?></span>
            </p>
        </div>
        <div class="pa-rt t10 r10">
            <img src="<?=Image::resize($model->image,100,100)?>" alt="<?=$model->title;?>" width="75" height="75">
        </div>
    </div>

    <ul class="pt10 pb10 row bdt tc gray9">
        <li class="col-3 bdr">
            <i class="iconfont f14">&#xe603;</i> <?=$model->tickets;?>
        </li>
        <li class="col-3 bdr">
            <i class="iconfont f16">&#xe645;</i> <?=$model->comment_count;?>
        </li>
        <li class="col-3">
            <i class="iconfont f16">&#xe643;</i> <?=$model->like_count;?>
        </li>
    </ul>
</a>