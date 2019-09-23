<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/10
 * Time: 15:43
 */
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18">评论列表</h2>
</header>

<section class="veiwport">
    <div class="p10 whitebg">
<?= \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemOptions' => ['class' => 'item'],
    'emptyTextOptions' => ['class' => 'empty tc p10 '],
    'itemView' => '_comment_view',
    'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
        'triggerTemplate'=>'<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">{text}</button></div>',
        'noneLeftTemplate'=>'<div class="ias-noneleft tc p10">{text}</div>',
        'eventOnRendered'=>'function() { $("img.lazy").scrollLoading();}',
    ]
]); ?>
    </div>
</section>
    <!--~~ 回复浮层 ~~-->
    <div class="reply-pop  whitebg" style="display:none;height: 110px;width: 70%!important;">
        <div class="p10">
            <form id="replay">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
                <input type="hidden" name="type">
                <input type="hidden" name="type_id">
                <textarea class="textarea  bd w" style="" name="content" placeholder="内容,3-700字"></textarea>
                <p class="tc mt5">
                    <button type="button" id="replySend" class="btn sbtn p10 greenbtn  white">回复点评</button>
                </p>
            </form>
        </div>
    </div>
<?php $this->beginBlock('JS_END') ?>
    /*~~ 回复评论 ~~*/
    $(".reply-btn").live('click',function() {
    maskdiv($(".reply-pop"),"center");
    $("#replay input[name='type']").val($(this).attr("data-type"));
    $("#replay input[name='type_id']").val($(this).attr("data-type-id"));
    $("#replay textarea[name='content']").val($(this).attr("data-content"));
    });
    $(".like-btn").live('click',function() {
    var like= $(this);
    $.get('<?=\yii\helpers\Url::to(['/club-like/submit'])?>',{'type':$(this).attr("data-type"),'type_id':$(this).attr("data-type-id")},function(data,status){
    if(data){
    alert(data);
    }else{
    like.html("&#xe626;");
    window.location.reload();
    }
    });
    });
    $("#replySend").live("click",function(e){
    $.post('<?=\yii\helpers\Url::to(['/club-comment/submit'])?>',$("#replay").serialize(),function(data,status){
    if(data){
    alert(data);
    }else{
    e.stopPropagation();
    $(".reply-pop").slideUp();
    $(".maskdiv").fadeOut();
    window.location.reload();
    }
    });
    });
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
<?php
h5\widgets\Wx\ShowImg::widget();
?>
