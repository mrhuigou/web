<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/3/29
 * Time: 10:04
 */
use yii\helpers\Url;
if($new_category){
    $this->title = $new_category->name;
}else{
    $this->title = '每日惠购快报';
}

?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="javascript:history.back();">
            <i class="aui-icon aui-icon-left green f28"></i>
        </a>
        <div class="flex-item-8 f16">
            <?= \yii\helpers\Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2" href="<?=\yii\helpers\Url::to(['/user/index'])?>">
            <i class="iconfont green f28">&#xe603;</i>
        </a>
    </div>
</header>
<div class="content">
	<div class="Items">
		<?php if($model){ ?>
		<div class="item-wrap item-hori mt10" id="list">
			<?= $this->render('_list_item', [
				'models' => $model,
			]) ?>
		</div>
		<div class="weui-infinite-scroll">
			<div class="infinite-preloader"></div>
			正在加载...
		</div>
	<?php }else{?>
		<p>当前没有任何数据</p>
	<?php } ?>
	</div>
</div>
<?= h5\widgets\MainMenu::widget(); ?>
<?php $this->beginBlock('JS_END') ?>
    var loading = false;
    var page=1;
    var page_count=<?=$page_count?>;
    $(".content").infinite().on("infinite", function() {
    if(loading) return;
    if(page < page_count){
    loading = true;
    $.get('<?=Url::to(['/read-more/index'])?>',{page:page},function(html){
    if(html){
    $("#list").append(html);
    loading = false;
    page++;
    }
    });
    }else{
    $(".weui-infinite-scroll").remove();
    }
    });
    var nav_pos=window.location.hash;
    if(nav_pos){
    var top_pos=$(nav_pos).offset().top;
    $(".content").animate({ scrollTop: top_pos-50}, 1000);
    }
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>