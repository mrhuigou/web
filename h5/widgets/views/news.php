<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/7
 * Time: 16:20
 */
?>

<!--家润快报-->
<div class="breakingNews flex-col aui-border mt5 mb5 none" id="breakingNews">
	<div class="bn-title flex-item-2">
		<p>家润</p>
		<p>快报</p>
	</div>
	<div class="flex-item-10 pl10 gray6">
		<ul>
			<?php foreach($model as $value){?>
			<li><a href="<?=$value->link_url?$value->link_url:\yii\helpers\Url::to(['/news/index','news_id'=>$value->news_id])?>" class="<?=$value->highlight?'red':''?>" ><?=\yii\helpers\Html::encode($value->title)?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php $this->registerJsFile("/assets/script/breakingnews.js",['depends'=>[\h5\assets\AppAsset::className()]])?>
<?php $this->beginBlock("JS")?>
/*新闻播放*/
$("#breakingNews").breakingNews({
effect		:"slide-v",
autoplay	:true,
timer		:3000,
color		:'green',
border		:true
});
<?php $this->endBlock()?>
<?php
$this->registerJs($this->blocks['JS'],\yii\web\View::POS_READY);
?>

