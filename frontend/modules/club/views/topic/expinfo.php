<?php
$this->title=\yii\helpers\Html::encode($model->title)."-话题-全城互动 - 家润慧生活（mrhuigou.com）- 青岛首选综合性同城网购-发现达人体验-分享同城生活";
?>
<div class="w1100 bc">
    <div class="layout grid-m0s5">
        <div class="col-m ">
            <div class="main-w  ">
                <h2 class="f16  p5 "><?=\yii\helpers\Html::encode($model->title)?></h2>
                <div class="bd_dashB clearfix p5 mb10">
                    <?php if($model->customer){ ?>
                    <div class="fl f14">作者：<?=$model->customer->nickname?> ; &nbsp; &nbsp;  发布时间：<?=date('m月d日 H:i',strtotime($model->create_time))?></div>
                    <?php }else{?>
                        <div class="fl f14">作者：慧生活官方  &nbsp; &nbsp;发布时间：<?=date('m月d日 H:i',strtotime($model->create_time))?></div>
                    <?php }?>
                    <div class="fr f14">
                        <span>阅读  <?=$model->total_click?> </span>
                    </div>
                </div>
                <div class="p10 con-detail oh">
                    <?=\yii\helpers\Html::decode($model->content)?>
                </div>
                <!--评论-->
                <?=\frontend\modules\club\widgets\ClubComment::widget(['type_name_id'=>'1','content_id'=>$model->exp_id,'route'=>\yii\helpers\Url::to(['/club/club-comment/index'])])?>
            </div>
        </div>
        <div class="col-s">
            <div class="bd p10 mb10">
                <p class="tc lh200 f14 pl20">微信扫一扫，分享给好友</p>
                <p class="tc"><img alt="" src="<?=\yii\helpers\Url::to(['/club/qrcode/index','data'=>'http://m.mrhuigou.com/'])?>" width="98" height="98"></p>
            </div>
            <div class="mt10">
                <?=frontend\modules\club\widgets\HotExp::widget()?>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
$("body").on('click','.like-btn',function() {
var like= $(this);
$.post('<?=\yii\helpers\Url::to(['/club/like/submit'])?>',{'type':$(this).attr("data-type"),'type_id':$(this).attr("data-type-id")},function(data,status){
if(data){
alert(data);
}else{
like.html("&#xe61f; ");
window.location.reload();
}
});
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
