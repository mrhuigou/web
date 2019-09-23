<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use \common\component\image\Image;
use \common\component\Helper\Datetime;
use \yii\helpers\Url;
$this->title =$model->title.'---全城互动';
?>

<header class="header w" id="header">
    <div class="tit-left">
        <a href="<?=Url::to(['/club-activity/index'])?>" >
            <span class="iconfont">&#xe63d;</span>
            <span class="f14">列表</span>
        </a>
    </div>
    <h2 class="tc f18">活动</h2>
    <div class="tit-right">
        <a href="javascript:;" class="share-guide">
            <span class="f14">邀请</span>
            <span class="iconfont">&#xe644;</span>
        </a>
    </div>
</header>
<?php if($model->music){?>
    <div id="audio_btn" style="display: block;">
        <div id="yinfu" class="rotate"></div>
        <audio loop="true" src="<?=Image::resize($model->music,100,100,9)?>" id="media" autoplay="autoplay"  preload="true"></audio>
    </div>
<?php }?>
<section class="veiwport">
    <div class="p10 whitebg mt10 " >
        <h1 class="f16 mb10 fb"><?=Html::encode($model->title)?></h1>
        <div class="clearfix f13 mb10">
            <div class="fl mt1">
                <i class="mr5 metro-basic bluebg-">
                    <?=$model->fee==0?"免费":($model->fee==2?"AA制":"收费")?>
                </i>
            </div>
            <div class="fr">
                <span class="gray9 ">阅读 <?=$model->click_count?> +</span>
            </div>
        </div>
        <div class="f5bg mb10">
            <p class="p5 bdb">
                <i class="iconfont gray6 f16">&#xe660;</i> <span class="gray6"><?=date('m月d日 H:i',strtotime($model->begin_datetime))?>( <?=\common\component\Helper\Datetime::getWeekDay(strtotime($model->begin_datetime))?> ) 到 <?=date('m月d日 H:i',strtotime($model->end_datetime))?></span>
                <br/><span class="gray9 ml5 pl10"><?=date('m月d日',strtotime($model->signup_end))?> 报名截止</span>
            </p>
            <p class="p5 bdb">
                <i class="iconfont gray6 f16">&#xe65f;</i> <span class="blue"><?=$model->address?$model->address:'暂未确定'?></span>
            </p>
            <p class="p5">
                <i class="iconfont gray6 f16">&#xe603;</i>
                <span class="gray6">已报 <i class="red"><?=$model->tickets?></i> 名额 </span>
                <span class="gray9">
                    （<?php if($model->qty) { ?>限 <i class="red"><?=$model->qty?></i> 名额<?php }else{ ?> 不限名额<?php } ?>）
                </span>
            </p>
        </div>
        <div>
            <img src="<?=Image::resize($model->image)?>" class="w" >
        </div>
        <div class="con-detail mt10">
            <?=Html::decode($model->description)?>
        </div>
        <div class="blue f13 mt30 mb10 clearfix">
					<span class="fr">
						<a class=" like-btn iconfont f16 red" href="javascript:;" data-type="activity" data-type-id="<?=$model->id?>"><?=$model->likeStatus ? "&#xe626;":"&#xe643;"?> </a>赞(<?=$model->like_count?>+)
					</span>
        </div>
        <?php if($model->userLike){ ?>
        <div class="bd-f5 blue p10 pr f5bg">
                <p class="f13">
                    <i class="iconfont f16 red">&#xe643;</i>
                    <?php foreach($model->userLike as $key=>$user){
                       if($key<5){ ?>
                        <span><?=$user->customer->nickname?></span>,
                    <?php }} ?>等觉得很赞！
                </p>
        </div>
        <?php } ?>
        <?php if($model->user){?>
        <div class="f5bg mt15 mb15">
            <p class="p9 gray9 f14"><?=count($model->user)?>人已完成报名</p>
            <?php foreach($model->user as $key=>$user){
                if($key<2){
             ?>
            <div class="clearfix p9 bdt">
                <div class="ava xsava fl"><img src="<?=Image::resize($user->order->customer?$user->order->customer->photo:"",45,45)?>" alt="<?=$user->order->customer?$user->order->customer->nickname:""?>" class="w pop-show"></div>
                <p class="tc mt2 ml5 f14 blue fl"><?=$user->order->customer?$user->order->customer->nickname:"匿名"?></p>
                <span class="fr mt2 gray9"><?=Datetime::getTimeAgo($user->order->date_added)?></span>
            </div>
            <?php }} ?>

            <a class="blue tc f14 pb15 db w" href="<?=\yii\helpers\Url::to(['/club-activity/user','id'=>$model->id])?>" >查看更多 <i class="iconfont f16">&#xe60a;</i></a>
        </div>
        <?php } ?>
    </div>

    <div class="whitebg p10">
        <div class="tit1 bluetit1  pb10 bdb">
            <a href="<?=Url::to(['/club-comment/index','type'=>'activity','type_id'=>$model->id])?>" class="f14 blue fr">更多 &gt;</a>
            <h2 class="gray6">精彩点评</h2>
        </div>
        <?php if($comments){ ?>
            <?php foreach($comments as $comment){ ?>
                <!--动态-->
                <div class="issuance ">
                    <figure class="pt15 clearfix">
                        <img src="<?=Image::resize($comment->customer->photo,45,45)?>" alt="ava" width="45" height="45" class="ava sava mr10 fl pop-show">
                        <figcaption class="ml50 pl5">
                            <p class="mb5 f14 blue "><?=$comment->customer->nickname?></p>
                            <p class="f12 wordbreak"><?=\yii\helpers\Html::encode($comment->content)?></p>
                        </figcaption>
                    </figure>
                    <?php if($comment->tag){?>
                        <div class="clearfix mt10">
                            <?php foreach($comment->tag as $tag){ ?>
                                <span class="labeltag orglabel"><?=$tag->tag->name?></span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if($comment->images){
                        $images=explode(",",$comment->images);
                        ?>
                        <ul class="clearfix">
                            <?php  foreach($images as $value){ ?>
                                <li class="pw33 fl mt10" >
                                    <img class="db pw92 bc pop-show"  data-img="<?=$value?>" src="<?=Image::resize(str_replace(\Yii::$app->params['HTTP_IMAGE'],"",$value))?>">
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                    <!--回复-->
                    <div class="gray9 mt10 mb10">
                        <span class="fr">
                            <a  href="javascript:;" class="mr10 like-btn iconfont vm f14  " data-type="comment" data-type-id="<?=$comment->id?>"><?=$comment->likeStatus?"&#xe626;":"&#xe643;"?></a>
                           <a href="javascript:;" class="reply-btn" data-type="comment" data-type-id="<?=$comment->id?>">回复</a>
                        </span>
                        <span class="tahoma"><?=Datetime::getTimeAgo($comment->creat_at)?></span>
                    </div>
                    <?php if($comment->reply){ ?>
                        <div class=" br5  p10 pr f5bg">
                            <?php if($comment->reply){ ?>
                                <?php foreach($comment->reply as $replay){?>
                                    <p class="mb5">
                                        <a href="javascript:;" class="reply-btn blue" data-type="comment" data-type-id="<?=$comment->id?>" data-content="@<?=$replay->customer?$replay->customer->nickname:"匿名"?> "><?=$replay->customer?$replay->customer->nickname:"匿名"?>：</a><span><?=\yii\helpers\Html::encode($replay->content)?></span><em class="ml5 f12 gray9"><?=Datetime::getTimeAgo($replay->creat_at)?></em>
                                    </p>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <a class="blue tc f14 pb15 db w" href="<?=Url::to(['/club-comment/index','type'=>'activity','type_id'=>$model->id])?>" >查看更多 <i class="iconfont f16">&#xe60a;</i></a>
        <?php }else{ ?>
            <div class="list-view" id="w0"><div class="empty tc p10 ">还没有评论哟~</div></div>
        <?php } ?>
    </div>
    <div class=" tri  tc  fx-bottom whitebg p10  bs-top" style="z-index: 1000">
        <a href="<?=\yii\helpers\Url::to(['/club-comment/apply','type'=>'activity','type_id'=>$model->id])?>"  class=" btn mbtn greenbtn">发表点评</a>
        <?php if(strtotime($model->signup_end)<time()){?>
            <a href="javascript:;" class=" btn mbtn graybtn">报名结束</a>
        <?php }else{ ?>
            <a href="<?=\yii\helpers\Url::to(['/club-activity/apply','id'=>$model->id])?>" class=" btn mbtn greenbtn">申请报名</a>
        <?php } ?>
    </div>
</section>

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

$("#audio_btn").click(function(){
var music = document.getElementById("media");
if(music.paused){
music.play();
$("#yinfu").removeClass("off");
}else{
music.pause();
$("#yinfu").addClass("off");
}
});
function audioAutoPlay(id){
var audio = document.getElementById(id);
audio.play();
document.addEventListener("WeixinJSBridgeReady", function () {
audio.play();
}, false);
document.addEventListener('YixinJSBridgeReady', function() {
audio.play();
}, false);
}
audioAutoPlay('media');
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
<?php h5\widgets\Tools\Share::widget(['data'=>[
	'title' =>$model->title.'-全城互动',
	'desc' => $model->meta_description ? $model->meta_description : $model->title,
	'link' => Yii::$app->request->getAbsoluteUrl(),
	'image' =>Image::resize($model->image,100,100)
]]);
