<style>
    .layermchild{
        box-shadow:none;
    }
</style>
<?php if ($data) { ?>
<div id="loading-page" style="display:none;">
	<div class="pr">
		<a class="pa-rt iconfont  close-loading whitebg ava sava" href="javascript:;" style="top: -10px;right:0px;line-height:40px; ">&#xe612;</a>
		<?php foreach (array_slice($data,0,1) as $value) { ?>
		<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="popshow">
			<img src="<?= \common\component\image\Image::resize($value->source_url, 640, 200,9) ?>" class="pw90">
		</a>
		<?php } ?>
	</div>
</div>
		<?php $this->beginBlock('JS_END') ?>
        function PopAd(){
        var mes=$("#loading-page").html();
        var index =  layer.open({
        time:300000,
        content: mes,
        style: 'background:none; border:none;',
        });
        }
        $("body").on('click','.close-loading', function () {  layer.closeAll();  });
        $("body").on('click','.popshow', function () { layer.closeAll();  });
         PopAd();
		<?php $this->endBlock() ?>
		<?php
		$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
		?>
<?php } ?>

