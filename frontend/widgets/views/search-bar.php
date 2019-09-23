<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/8
 * Time: 10:55
 */
?>
<form action="<?php echo yii\helpers\Url::to(["/category/index"])?>" method="get" id="search_form">
	<div class="search-box clearfix" style="margin-left: 150px;">
		<input type="text" value="<?=\yii\helpers\Html::encode(Yii::$app->request->get('keyword'))?>" name="keyword"  class="fl" placeholder="搜一下，想找的宝贝">
		<button class="btn-search fr search-btn">搜索</button>
	</div>
</form>
<?php if($keyword){?>
	<div class="mt1" style="margin-left:150px;">
		<?php foreach($keyword as $value){?>
			<a class="pl10 pr10 <?=$value->color?>" href="<?=$value->url?>"><?=$value->name?></a>
		<?php } ?>
	</div>
<?php }?>
