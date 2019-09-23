<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/9/25
 * Time: 20:41
 */
?>
<a href="<?=$model->link_url?$model->link_url:\yii\helpers\Url::to(['news/index','news_id'=>$model->news_id])?>">
<div class="m10 p10 bd whitebg br5">
	<h2 class="fb lh200 ellipsis <?=$model->highlight?"red":''?>"><?=$model->title?></h2>
	<p class="lh150 gray9 mt5 mb5 "><?=date("Y-m-d",strtotime($model->date_modified?$model->date_modified:$model->date_added))?></p>
	<?php if($model->image){?>
	<p><img src="<?=\common\component\image\Image::resize($model->image,640,200,9)?>" class="lazy w db" ></p>
	<?php }?>
	<div class="lh200 gray9 mt5 "><?=$model->meta_description?></div>
	<!--阅读全文-->
	<a href="<?=$model->link_url?$model->link_url:\yii\helpers\Url::to(['news/index','news_id'=>$model->news_id])?>" class="db mt5 bdt pt5">
		<i class="iconfont fr grayc f12">&#xe60b;</i>
		<span class="gray6 f12">阅读全文</span>
	</a>
</div>
</a>