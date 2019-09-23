<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/20
 * Time: 15:17
 */
?>
<div class="whitebg flex-col aui-border  mt5 mb5 ">
	<div class="flex-item-3 p5 flex-middle flex-row flex-center ">
			<i class="iconfont red f30">&#xe66e;</i>
	</div>
	<div class="flex-item-6 flex-m p5 pt10 pb10">
		<h2 class="f16"><?=$model->name?></h2>
		<span class="gray9"><?=date('m/d',$model->create_at)?></span>
	</div>
	<?php if(count($model->history)>=2){?>
	<a  class="flex-item-3 flex-m tc graybg "  href="<?=\yii\helpers\Url::to(['/share/gift','hongbao_id'=>$model->id,'auth'=>md5($model->id)],true)?>">
		<span  class="db" style="line-height: 63px;">
		查看详情
		</span>
	</a>
	<?php }else{?>
		<a  class="flex-item-3 flex-m tc redbg white"  href="<?=\yii\helpers\Url::to(['/share/gift','hongbao_id'=>$model->id],true)?>">
		<span  class="db" style="line-height: 63px;">
		去分享
		</span>
		</a>
	<?php }?>
</div>
