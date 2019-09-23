<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use \common\component\Helper\Helper;
use \common\component\image\Image;
$this->title =$model->title.'---免费试';
use \yii\helpers\Url;
?>
<header class="header w" id="header">
    <div class="tit-left">
        <a href="<?=Url::to(['/club-try/index'])?>" >
            <span class="iconfont">&#xe63d;</span>
            <span class="f14">列表</span>
        </a>
    </div>
    <h2 class="tc f18">免费试详情</h2>
    <div class="tit-right">
        <a href="javascript:;" class="share-guide">
            <span class="f14">邀请</span>
            <span class="iconfont">&#xe644;</span>
        </a>
    </div>
</header>
<section class="veiwport">
    <div class="p10 whitebg">
        <p class="tc">
            <img data-original="<?=Image::resize($model->image,640,270)?>?>" alt="<?=$model->title?>" class="lazy db w">
        </p>
        <h2 class="f14 fb  pt10 lh200"><?=$model->title?></h2>
        <div class="lh200 fb clearfix">
            <p class="fl">价值：<span class="red  f14">￥<?=$model->product?$model->product->price:"---"?></span></p>
            <p class="fr">免费提供：<span class="red  f14"><?=$model->quantity?$model->quantity."份":"数量不限"?></span></p>
        </div>
        <div class="graybg w clearfix p5 ">
            <p class="fb lh200 org">申请条件及规则</p>
            <p class="lh200 ">
                1. 所有会员都可以免费申请。<br>
                2. 提交申请后，邀请 <span class="org fb"><?=$model->limit_share_user?$model->limit_share_user:0?></span> 人参与本活动，即可获取免费试资格。<br>
                3. 若没有获取到免费试资格的会员，我们将在<?=date('m/d H:i',strtotime($model->end_datetime))?>进行抽奖。<br>
                4. 所有获取免费资格的用户，无需支付邮费，我们将每周一统一发货。
            </p>
        </div>
        <div class="org f13 mt30 mb10 clearfix">
					<span class="fr">
						<a class=" like-btn iconfont f16" href="javascript:;" data-type="try" data-type-id="<?=$model->id?>"><?=$model->likeStatus ? "&#xe626;":"&#xe643;"?> </a>赞(<?=$model->like_count?>+)
					</span>
        </div>
        <?php if($model->userLike){ ?>
            <div class="bd-f5 org p10 pr mb10 f5bg">
                <div class="arr-bd toparr-bd" style="left:auto;right:35px;">
                    <span></span>
                </div>
                <p class="f13">
                    <i class="iconfont f16">&#xe643;</i>
                    <?php foreach($model->userLike as $key=>$user){
                        if($key<5){ ?>
                            <span><?=$user->customer->nickname?></span>,
                        <?php }} ?>等觉得很赞！
                </p>
            </div>
        <?php } ?>
        <?php if($model->user){?>
            <div class="pt10 pb10 bdb-d bdt-d pr">
                <a href="<?=\yii\helpers\Url::to(['/club-try/user','id'=>$model->id])?>" class="fr mt10 pt2 ava-num"><span class="org"><?=count($model->user)?></span>人参与</a>
                <ul class="clearfix ava-box">
                    <?php foreach($model->user as $user){ ?>
                        <li class="ava sava mr10 fl"><img src="<?=Image::resize($user->customer->photo,45,45)?>" alt="<?=$user->customer->nickname?>" class="w pop-show"></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <div class="pt10 pb10 bdb-d bdt-d f12 ">
            <div class="con-detail">
                <?= Html::decode($model->description) ?>
            </div>
        </div>
        <!--标题-->
        <div class="tit1 p5 mt10">
            <h2>精彩点评</h2>
        </div>
        <?php if($comments){ ?>
            <?php foreach($comments as $comment){ ?>
                <!--动态-->
                <div class="issuance">
                    <figure class="pt15 clearfix">
                        <img src="<?=Image::resize($comment->customer->photo,45,45)?>" alt="ava" width="45" height="45" class="ava sava mr10 fl pop-show">
                        <figcaption class="ml50 pl5">
                            <p class="mb5 f14"><?=$comment->customer->nickname?></p>
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
                                    <img class="db pw92 bc pop-show"  data-img="<?=$value?>" src="<?=Image::resize(str_replace(\Yii::$app->params['HTTP_IMAGE'],"",$value),100,100)?>">
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
                        <span class="tahoma"><?=Helper::Format_date(strtotime($comment->creat_at))?></span>
                    </div>
                    <?php if($comment->reply){ ?>
                        <div class=" br5 bs p10 pr f5bg">
                            <!--箭头指向时间啊 ^ -->
                            <div class="arr-bd toparr-bd">
                                <span></span>
                            </div>
                            <?php if($comment->reply){ ?>
                                <?php foreach($comment->reply as $replay){?>
                                    <p class="mb5">
                                        <a href="javascript:;" class="reply-btn blue" data-type="comment" data-type-id="<?=$comment->id?>" data-content="@<?=$replay->customer?$replay->customer->nickname:"匿名"?> "><?=$replay->customer?$replay->customer->nickname:"匿名"?>：</a><span><?=\yii\helpers\Html::encode($replay->content)?></span><em class="ml5 f12 gray9"><?=Helper::Format_date(strtotime($replay->creat_at))?></em>
                                    </p>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="w tc mt10 "><a class=" appbtn pr10 pl10  " href="<?=Url::to(['/club-comment/index','type'=>'try','type_id'=>$model->id])?>">查看更多...</a></div>
        <?php }else{ ?>
            <div class="list-view" id="w0"><div class="empty tc p10 ">还没有评论哟~</div></div>
        <?php } ?>
        <div class=" tri  tc  fx-bottom whitebg p10  bs-top" style="z-index: 1000">
            <?php if(!$model->join){ ?>
                <?php if(strtotime($model->begin_datetime)>time()){ ?>
                    <a href="javascript:;" class=" btn mbtn graybtn">尚未开始</a>
                <?php }elseif(strtotime($model->end_datetime)<time()){ ?>
                    <a href="javascript:;" class=" btn mbtn graybtn">已经结束</a>
                    <a href="<?=\yii\helpers\Url::to(['/club-try/user','id'=>$model->id])?>" class=" btn mbtn greenbtn">查看中奖名单</a>
                <?php }else{ ?>
                    <a href="<?=\yii\helpers\Url::to(['/club-try/apply','id'=>$model->id])?>" class=" btn mbtn greenbtn">申请报名</a>
                <?php } ?>
            <?php }else{ ?>
                <a href="<?=\yii\helpers\Url::to(['/club-comment/apply','type'=>'try','type_id'=>$model->id])?>"  class=" btn mbtn greenbtn">发表点评</a>
                <?php if(strtotime($model->end_datetime)<time()){ ?>
                    <a href="<?=\yii\helpers\Url::to(['/club-try/user','id'=>$model->id])?>" class=" btn mbtn greenbtn">查看中奖名单</a>
                <?php }else{ ?>

                <?php } ?>
            <?php } ?>
			<a class="btn mbtn greenbtn " href="<?=\yii\helpers\Url::to(['/club-try/invite','id'=>$model->id])?>">邀请记录</a>
            <a href="<?=\yii\helpers\Url::to(['product/index','product_base_id'=>$model->product_base_id])?>"  class=" btn mbtn redbtn">点击购买</a>
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
h5\widgets\Wx\Share::widget([
    'title'=>$model->title.'-免费试吃',
    'desc'=>$model->title.'-免费试吃',
    'link'=>Yii::$app->request->getAbsoluteUrl(),
    'imgUrl'=>Image::resize($model->image,100,100)
]);
?>