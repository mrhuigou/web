<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:57
 */
?>
	<div class="top-banner">
		<span class="slide-icon">
			<em>收起</em>
			<i class="iconfont">&#xe630;</i>
		</span>
<?php foreach(array_slice($data,0,1) as $key=>$value){?>
		<a href="<?=$value->link_url?>"><div  class="top-banner-small"> <img src="<?=\common\component\image\Image::resize($value->source_url,$value->width,$value->height,9)?>" class="db w"></div></a>
<?php } ?>
		<?php foreach(array_slice($data,1,1) as $key=>$value){?>
			<a href="<?=$value->link_url?>"><div  class="top-banner-big"> <img src="<?=\common\component\image\Image::resize($value->source_url,$value->width,$value->height,9)?>" class="db w"></div></a>
		<?php } ?>
	</div>
<?php $this->beginBlock('JS_END') ?>
	/*开网大banner*/
	$(".slide-icon").click(function(){
	clearTimeout(test);
	if($(".top-banner-big").is(":visible")){
	$(".top-banner-big").hide();
	$(".top-banner-small").slideDown();
	$(this).find("em").text("展开").end().find("i").html("&#xe62f;");
	}else{
	$(".top-banner-big").slideDown();
	$(".top-banner-small").hide();
	$(this).find("em").text("收起").end().find("i").html("&#xe630;");
	}
	});
	var test = setTimeout(function(){
	$(".slide-icon").trigger('click');
	},3000);

<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>