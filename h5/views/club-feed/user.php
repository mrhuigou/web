<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/8/20
 * Time: 14:22
 */
use \yii\helpers\Html;
use \yii\helpers\Url;
$this->title="我的发现";
?>
    <header class="header w" id="header">
    <div class="tit-left">
        <a href="javascript:history.back();" class="his-back">返回</a>
    </div>
    <h2 class="tc f18">我的发现</h2>
</header>
<section class="veiwport">
    <div class="p10 whitebg">
        <div class="discovery-hd" >
            <div class="ava-box">
                <img src="<?= \common\component\image\Image::resize($customer->photo,45,45);?>" alt="ava" width="45" height="45" class="ava sava fr pop-show">
                <p class="fr white mr10 pt10 tr">
                    <span><?= $customer->nickname;?></span> <br>
                </p>
            </div>
        </div>
        <!--动态-->
        <?= \yii\widgets\ListView::widget([
            'layout' => "{items}\n{pager}",
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'emptyTextOptions' => ['class' => 'empty tc p10 '],
            'itemView' => '_item_user_view',
            'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
                'triggerTemplate'=>'<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">{text}</button></div>',
                'noneLeftTemplate'=>'<div class="ias-noneleft tc p10">{text}</div>',
            ]
        ]); ?>

        <!--~~ 回复浮层 ~~-->
        <div class="reply-pop  whitebg" style="display:none;height: 110px;width: 70%!important;">
            <div class="p10">
                <form id="replay">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
                    <input type="hidden" name="type">
                    <input type="hidden" name="type_id">
                    <textarea  class="textarea bd w"  name="content" placeholder="内容,3-700字"></textarea>
                    <p class="tc mt5">
                        <button type="button" id="replySend" class="btn sbtn p10 greenbtn  white">回复点评</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
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
    location.href='<?=\yii\helpers\Url::to(['/club-feed/index'],true)?>';
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
<?=h5\widgets\MainMenu::widget();?>