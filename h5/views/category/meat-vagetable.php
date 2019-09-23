<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='蔬菜禽肉专场';
?>
<header class="header w" id="header">
    <a class="pa-lt iconfont leftarr" href="javascript:history.back();" ></a>
    <div class="pr pl30 pr5 ">
        <form action="<?php echo \yii\helpers\Url::to(['/search/index'])?>" method="get" id="search_form">
            <input class="input-text minput w "  type="text" name="keyword" value="<?=Html::encode(Yii::$app->request->get('keyword'))?>" autocomplete="off" tabindex="1">
            <a href="javascript:void(0)" class="search-btn iconfont">&#xe601;</a>
        </form>
    </div>
</header>
<section>
    <?php if($ad_img){?>
        <?php foreach ($ad_img as $value){?>
            <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" >
                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="bc db " style="height: 7.5rem; width: 32rem;">
            </a>
        <?php }?>
    <?php }?>

    <div class="flex-col pt50">

        <div class="flex-item-3 cate-sidenav f12" style="top:calc(7.5rem + 63px);">

            <?php if($top_advertises){?>
                <?php foreach ($top_advertises as $top_advertise){?>
                    <a class="ajax <?php if($current == 'adv_id_'.$top_advertise->advertise_id){ echo 'cur';}?>"  href="<?php echo \yii\helpers\Url::to(['category/meat-vegetable','advertise_id'=>$top_advertise->advertise_id])?>" ><?php echo $top_advertise->name;?></a>
                <?php }?>
            <?php }?>
        <?php if($category_displays){?>
            <?php foreach ($category_displays as $category_display){?>
                <a class="ajax <?php if($current == 'cate_id_'.$category_display->category_display_id){ echo 'cur';}?>"  href="<?php echo \yii\helpers\Url::to(['category/meat-vegetable','cate_id'=>$category_display->category_display_id])?>" ><?php echo $category_display->description->name;?></a>
            <?php }?>
        <?php }?>

            <?php if($ad_text){?>
                <?php foreach ($ad_text as $key => $value) { ?>
                    <a href="<?= \yii\helpers\Url::to(\yii\helpers\Url::to($value->link_url, true), true) ?>">
                        <?php echo $value->title; ?>
                    </a>
                <?php } ?>
            <?php }?>
        </div>
        <div class="flex-item-9 cate-sidelist" style="top:calc(7.5rem + 58px);">
            <?php echo $content;?>
        </div>
    </div>

</section>

<?= h5\widgets\MainMenu::widget(); ?>
<?php $this->beginBlock('JS_END') ?>
(function (doc, win) {
var docEl = doc.documentElement,
resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
recalc = function () {
var clientWidth = docEl.clientWidth;
if (!clientWidth){
return;
}else if(clientWidth>640){
docEl.style.fontSize =20 + 'px';
}else{
docEl.style.fontSize = 20 * (clientWidth / 640) + 'px';
}
};
if (!doc.addEventListener) return;
win.addEventListener(resizeEvt, recalc, false);
doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);

Ad_Sys_Code('H5-2LTC-ADSC');
<?php $this->endBlock() ?>
<?php
$this->registerJsFile("/assets/script/page.category.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
