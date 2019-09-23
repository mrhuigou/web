<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
?>
<?php
$tag = "";
if($NewModel->category_id == 2){
    $img = "/assets/images/baby-kuaibao.png";
    $tag = 'baby';
}else{
    $img = "/assets/images/green-kuaibao.png";
}?>
<!-- 慧生活动态 -->
<?php if($model){?>
    <style>
        .BreakingNewsController{width:100%; overflow:hidden; background:#FFF;  position:relative; height: 55px;}
        .BreakingNewsController .bn-title{display:inline-block; float:left;  background:#FFF; color:#FFF;width: 90px;font-size: 14px; height: 55px;line-height: 55px;}
        .BreakingNewsController ul{padding:0; margin:0; display:block; list-style:none;float:left; position:absolute; }
        .BreakingNewsController ul li{list-style:none; padding:2px 5px; display:none;}
        .BreakingNewsController ul li a{text-decoration:none; color:#333; font-size:14px;display:inline-block; overflow:hidden; padding:0;white-space:nowrap;text-overflow:ellipsis;}
        .easing a, .easing span{transition:.25s linear; -moz-transition:.25s linear; -webkit-transition:.25s linear;}
    </style>
    <div class="BreakingNewsController easing mt5 mb5" id="breakingnews">
        <div class="bn-title" style="<?php if($tag == 'baby'){ echo "width: 65px !important;";}?>"></div>
        <ul>
            <li>
	        <?php foreach ($model as $key=>$value){?>
                <a href="<?=\yii\helpers\Url::to(['read-more/index','c'=>$value->news_category_id ,'#'=>'item_postion_'.$value->news_id])?>" class="<?=$value->highlight?"red":''?> db w " style="height: 25px;">
                    <?php if($value->tag){?><span class="btn btn-xxs btn-bd-red"><?=trim($value->tag)?></span><?php }?>
                    <?=$value->title?></a>
                <?php if(($key+1)%2==0 && ($key+1)<count($model)){?>
                </li><li>
                <?php } ?>
	        <?php }?>
            </li>
        </ul>
    </div>
<?php $this->beginBlock('JS_NEWS') ?>
/* 新闻播放 */

$('#breakingnews').BreakingNews({
title: '<img src="<?php echo $img;?>" style="width: auto;height: 55px;">',
timer: 4000,
effect: 'slide'
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_NEWS'],\yii\web\View::POS_READY);
?>
<?php } ?>