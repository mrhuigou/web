<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/20
 * Time: 15:17
 */
?>

<a class="whitebg flex-col aui-border  mt5 mb5 " href="<?=\yii\helpers\Url::to(['/share/gift','hongbao_id'=>$model->hongbao->id],true)?>">
	<div class="flex-item-3 p5 flex-middle flex-row flex-center ">
			<img src="<?=\common\component\image\Image::resize($model->hongbao->customer->photo,100,100)?>" alt="<?=$model->hongbao->customer->nickname?>" class="ava mava">
	</div>
	<div class="flex-item-6 flex-m p5 pt10 pb10">
		<h2 class="f16"><?=$model->hongbao->customer->nickname?></h2>
		<span class="gray9"><?=date('m/d H:i:s',$model->create_at)?></span>
	</div>
	<div  class="flex-item-3 flex-m tc redbg white">
		<span  class="db" style="line-height: 63px;">查看</span>
	</div>
</a>
