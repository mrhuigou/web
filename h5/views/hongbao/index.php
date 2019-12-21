<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/11
 * Time: 15:48
 */
$this->title = "喊人帮我拆红包";
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<div class="content" style="bottom: 0px;">
<div class="redbag-index">
	<div class="redbag-top">
		<a href="javascript:;">
			<img src="<?= \common\component\image\Image::resize($model->customer->photo, 50, 50) ?>" width="80" height="80" class="img-circle bd">
		</a>
		<p class="pt15 pb5">
			<span class="f18"><?=$model->customer->nickname?$model->customer->nickname:'好心人'?></span>
		</p>
		<?php if(Yii::$app->request->get('share_status')){?>
		<p class="f12 pb15">拼手气红包</p>
		<p class="f18 fb">
			恭喜发财，大吉大利！
		</p>
		<?php }else{?>
			<p class="f12 pb15">人气红包</p>
			<p class="f18 fb">
				分享到朋友圈</br>
				邀请好友帮我拆红包
			</p>
		<?php } ?>
	</div>
	<div class="tc">
		<?php if(Yii::$app->request->get('share_status')){?>
		<a href="javascript:;" class="redbag-open" id="open_hongbao">
			拆红包
		</a>
		<?php }else{?>
		<a href="javascript:;" class="redbag-open share-guide">
			分享
		</a>
		<?php } ?>
	</div>
</div>
</div>
<?php h5\widgets\Tools\Share::widget(['data'=>[
	'title' => '帮我拆红包，我要买买买!',
	'desc' => '酸奶、水果、饮料、日常用品。在每日惠购1元就“购”了!',
	'link' => \yii\helpers\Url::to(['/hongbao/index','id'=>$model->id,'share_status'=>1],true),
	'image' => Yii::$app->request->getHostInfo() . '/assets/images/hongbao_share.jpg'
]]);
?>
<?php $this->beginBlock('JS_END') ?>
$("#open_hongbao").on('click',function(){
$.showIndicator();
$.post('<?=\yii\helpers\Url::to(['/hongbao/open'],true)?>',{'id':'<?=$model->id?>'},function(data){
$.hideIndicator();
if(data.status){
window.location.href=data.redirect;
}else{
alert(data.message);
}
},'json');

});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
