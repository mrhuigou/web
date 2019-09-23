<?php if($model){ ?>
<h2 class="f14  p10 graybg ">精选活动</h2>
<?php foreach($model as $value){ ?>
<div class="pr mb10 ">
    <a href="<?=\yii\helpers\Url::to(['/club/default/info','id'=>$value->id])?>"><img src="<?=\common\component\image\Image::resize($value->image,230,230)?>" alt="<?=\yii\helpers\Html::encode($value->title)?>" width="230" height="230" class="db"></a>
    <p class="pa-b opc-0 p5 pl10 white"><?=\yii\helpers\Html::encode($value->title)?></p>
</div>
<?php } ?>
<?php } ?>