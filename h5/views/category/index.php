<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='商品分类';
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
    <div class="flex-col pt50 ">
        <div class="flex-item-3 cate-sidenav ">
            <?php foreach($model as $value){ ?>
                <a data-source="<?=\yii\helpers\Url::to(['category/children','cate_id'=>$value['id']])?>" href="javascript:;"><?=$value['name']?></a>
            <?php }?>
        </div>
        <div class="flex-item-9 cate-sidelist ">
        </div>
    </div>
</section>
<?= h5\widgets\MainMenu::widget(); ?>
<?php $this->beginBlock('JS_END') ?>
$(".cate-sidenav a").on("click",function(){
$(this).addClass("cur").siblings().removeClass("cur");
$.showIndicator();
$(".cate-sidelist").load($(this).attr("data-source"),function(){
$.hideIndicator();
});
});
var _this=$(".cate-sidenav").children("a").first();
_this.addClass("cur");
$(".cate-sidelist").load(_this.attr("data-source"),function(){
});
<?php $this->endBlock() ?>
<?php
$this->registerJsFile("/assets/script/page.category.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
