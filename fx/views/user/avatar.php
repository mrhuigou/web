<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title ='个人头像';
?>
<style>
	#clipArea {
		height: 300px;
	}
	#view {
		margin: 0 auto;
		width: 200px;
		height: 200px;
		background-color: #666;
        border-radius: 50%;
	}
	input[type=file],input[type=button]{position: absolute;top: 0;left: 0;bottom: 0;border: 0;padding: 0;margin: 0; height:100%; width: 100%; cursor: pointer; border: solid 1px #ddd;opacity: 0;}
	input[type=button]{opacity: 1;}
</style>
<header class="header w" id="header">
	<a href="javascript:history.back();" class="his-back">返回</a>
	<h2><?= Html::encode($this->title) ?></h2>
	<a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>

<section>
    <div class="bc w tc" id="avatar">
        <img src="<?=$model->photo?\common\component\image\Image::resize($model->photo):'/assets/images/defaul.png'?>" width="200" height="200"  class="img-circle">
    </div>
        <div id="clipArea" style="display: none"></div>
        <div id="view" style="display: none"></div>
	<div class="pl20 pr20 pt20">
		<div class="pr w" style="height:40px;">
			<a href="javascript:void(0);"  >
				<input type="button" name="file"  value="上传照片" >
				<input id="file" type="file" accept="image/*" multiple  />
			</a>
		</div>
	</div>
    <div class="w bc pl20 pr20 mt10 clipBtn" style="display: none;" >
	<a href="javascript:;" class="btn lbtn greenbtn  w" id="clipBtn" >截取并上传</a>
    </div>
    <div class="w bc pl20 pr20 mt10">
	<a href="javascript:;" class="btn lbtn greenbtn  w" id="weixinAvatar">使用微信头像</a>
    </div>
</section>
<?php $this->beginBlock('JS_END') ?>
var pc = new PhotoClip('#clipArea', {
size: 260,
outputSize: 640,
file: '#file',
view: '#view',
ok: '#clipBtn',
loadStart: function() {
$("#avatar").hide();
$("#view").hide();
$("#clipArea").show();
},
loadComplete: function() {
$(".clipBtn").show();
console.log('照片读取完成');
},
done: function(dataURL) {
$.showLoading("正在上传");
$.post("<?=\yii\helpers\Url::to(['/user/avatar'])?>",{data:dataURL},function(res){
$.hideLoading();
if(res.status){
$("#view").show();
$("#clipArea").hide();
$(".clipBtn").hide();
$.toast(res.message);
}else{
$.alert(res.message);
}
});
},
fail: function(msg) {
$.alert(msg);
}
});
$("#weixinAvatar").click(function(){
$.showLoading("正在加载");
$.post("<?=\yii\helpers\Url::to(['/user/wx-avatar'])?>",function(res){
$.hideLoading();
if(res.status){
$("#view").hide();
$("#avatar").find("img").attr("src",res.data);
$("#avatar").show();
$.toast(res.message);
}else{
$.alert(res.message);
}
});

});


<?php $this->endBlock() ?>
<?php
$this->registerJsFile("/assets/script/iscroll-zoom.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/hammer.min.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/lrz.all.bundle.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/PhotoClip.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>


