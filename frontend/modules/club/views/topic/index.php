<?php
$this->title="我的话题--生活圈";
?>
<div class="w1100 bc">
    <div class="layout grid-s5m0">
        <div class="col-m">
            <div class="main-w">
                <h2 class="f14 graybg p10">我围观的话题</h2>
                <div class="mb10 row" id="MyTopic">
                <?php if($my_subject){?>
                    <?php foreach($my_subject as $model){ ?>
                        <div class="pr col-4 cp subitem" >
                            <a href="<?php echo \yii\helpers\Url::to(['/club/topic/subject-list','sub_id'=>$model->subject->sub_id])?>" class="p5 db"><img src="<?=\common\component\image\Image::resize($model->subject->cover_image,225,225)?>" alt="<?=\yii\helpers\Html::encode($model->subject->title)?>"  class="db w"></a>
                            <p class="pa-b opc-0 p5 mr5 ml5 tc white" style="bottom:5px;"><?=\yii\helpers\Html::encode($model->subject->title)?></p>
                        </div>
                    <?php } ?>
                    <div class="p10 mt10 tc empty" style="display: none"> 您还没有围观任何话题</div>
                <?php }else{?>
                    <div class="p10 mt10 tc empty"> 您还没有围观任何话题</div>
                <?php } ?>
                </div>
                <h2 class="f14 graybg p10">可能感兴趣的话题</h2>
                <div class="mb10 row" id="Topic">
                    <?php foreach($subject as $model){ ?>
                        <div class="pr col-4 cp subitem" for="<?php echo $model->sub_id;?>" >
                            <a href="javascript:;" class="p5 db activityItem">
                                <img src="<?=\common\component\image\Image::resize($model->cover_image,225,225)?>" alt="<?=\yii\helpers\Html::encode($model->title)?>"  class="db w">
                                <span class="btn sbtn orgBtn w50 activityItemBtn">关注</span>
                            </a>
                            <p class="pa-b opc-0 p5 mr5 ml5 tc white" style="bottom:5px;"><?=\yii\helpers\Html::encode($model->title)?></p>
                        </div>
                    <?php } ?>
                    <div class="p10 mt10 tc empty" style="display: none"> 已经没有更多话题了</div>
                </div>
            </div>
        </div>
        <div class="col-s">
            <?=frontend\modules\club\widgets\Menu::widget()?>
        </div>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>

$("#Topic").on('click','.subitem',function(){
    var _this = $(this);
    var sub_id = _this.attr("for");
    if($("#MyTopic").find('.empty')){
        $("#MyTopic").find('.empty').hide();
    }
    $.post("<?php echo \yii\helpers\Url::to(['/club/topic/ajax-add-customer-subject'])?>", { "sub_id": sub_id },
        function(data){
            if(data.status == 'success'){
                var html = _this.clone();
                html.find('a').attr('href','/club/topic/subject-list?sub_id='+sub_id);
                html.find('.activityItemBtn').remove();
                $("#MyTopic").append(html);
                    _this.remove();
                if( $("#Topic").children('.subitem').length==0 ){
                    $("#Topic").find('.empty').show();
                }
            }else{
                alert(data.message);
            }
        }, "json");
});
/*
$("#MyTopic").on('click','.subitem',function(){
    $(this).remove();
    if( $("#MyTopic").children('.subitem').length==0 ){
        $("#MyTopic").find('.empty').show();
    }

});
*/
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
