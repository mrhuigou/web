<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/9/29
 * Time: 11:37
 */
use \common\component\image\Image;
$this->title=\yii\helpers\Html::encode($model->title)."---免费试";
?>
<div class="w1100 bc">
    <div class="layout grid-m0s5">
        <div class="col-m">
            <div class="main-w">
                <div class="bd p10 pr clearfix mb10">
                    <div class="fl w">
                        <div style="margin-left: 385px;">
                            <div class="mb20 mt10">
									<span class="fr gray9">
										<i>阅读 <?=$model->click_count?>+</i>
										<i>分享 <?=$model->share_count?>+</i>
									</span>
                                <h2 class="f16 w300 mxh20"><?=\yii\helpers\Html::encode($model->title)?></h2>
                            </div>
                            <div class="detailCon1">
                            <p class="mb20 green"><?=$model->product?$model->product->description->meta_description:''?></p>
                            <?php if($model->product){ ?>
                            <p class="mb15">市场价：<span class="f25 red">￥<?=$model->product->price?></span></p>
                            <?php } ?>
                            <p class="mb10 org0"><?=$model->end_datetime?> 准时开奖，限量<?=$model->quantity?> 份</p>
                            <p class="mb15 org0">成功邀请 <?=$model->limit_share_user?$model->limit_share_user:0?> 位好友参与免费试吃活动，可直接获得免费试吃机会哦！！</p>

                            <div class="box clearfix">
                                <span class='gray9 italic fl mt5 pt2'>距离开奖还有：</span>
                                <div class="countdown fl vm">
                                    <input type="text" id="time_d" class="tc"> 天 <input type="text" id="time_h" class="tc"> 时 <input type="text" id="time_m" class="tc"> 分 <input type="text" id="time_s" class="tc"> 秒
                                </div>
                            </div>

                            <div class="mt15 pb20 bd_dashB">
                                <?php if($model->join){ ?>
                                    <a class="btn btn_middle grayBtn mr5 " href="javascript:;"> 已经报名</a>
                                <?php } else{ ?>
                                <?php if(time()<strtotime($model->begin_datetime)){?>
                                    <a class="btn btn_middle disableBtn" style="border:1px solid #d60918;"> 尚未开始</a>
                                <?php }elseif(time()>strtotime($model->end_datetime)){?>
                                    <a class="btn btn_middle disableBtn" style="background-color: #999;color: white"> 试吃结束</a>
                                <?php } else{ ?>
                                    <a class="btn btn_middle greenbtn TryApplyBtn" href="javascript:;"> 我要报名</a>
                                <?php } ?>
                                <?php } ?>
                                <?php if($model->product){ ?>
                                    <a class="btn btn_middle redBtn" href="<?=\yii\helpers\Url::to(['/product/index','product_base_code'=>$model->product->product_base_code,'shop_code'=>$model->product->store_code])?>"> 直接购买</a>
                                <?php } ?>
                            </div>
                         </div>

                            <div class="f14 pa-b b15 r10" style="margin-left: 395px;">
									<span class="blue fr">
										<a class=" like-btn iconfont blue f20" href="javascript:;" data-type="try" data-type-id="<?=$model->id?>"><?=$model->likeStatus ? "&#xe61f;":"&#xe61e;"?></a> 赞(<?=count($model->userLike)?>+)
									</span>
                                <span class="red"><?=count($model->user)?></span>人已申请，赶快报名申请吧~
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" class="fl" style="margin-left: -100%;"><img src="<?=Image::resize($model->product?$model->product->image:'',350,350)?>" alt="tu" width="350" height="350" class="db"></a>
                </div>

                <div class="bd_dashB p5 mb10 f14 tit1">
                    <h2 class="gray9">活动详情</h2>
                </div>
                <div class="p10 con-detail oh">
                    <?=\yii\helpers\Html::decode($model->description)?>
                </div>
                <div class="blue f13 mt30 mb10 clearfix">
					<span class="fr">
						<a class=" like-btn iconfont blue f16" href="javascript:;" data-type="try" data-type-id="<?=$model->id?>"><?=$model->likeStatus ? "&#xe61f;":"&#xe61e;"?> </a>赞(<?=$model->like_count?>+)
					</span>
                </div>
                <?php if($model->userLike){ ?>
                    <div class="bd-f5 blue p10 pr f5bg mb10">
                        <div class="arr-bd toparr-bd" style="left:auto;right:35px;">
                            <span></span>
                        </div>
                        <p class="f13">
                            <i class="iconfont f16">&#xe61e;</i>
                            <?php foreach($model->userLike as $key=>$user){
                                if($key<5){ ?>
                                    <span><?=$user->customer?$user->customer->nickname:'###'?></span>,
                                <?php }} ?>等觉得很赞！
                        </p>
                    </div>
                <?php } ?>
                <!--已报用户-->
                <div class="bd_dashB p5 mt15 mb15 f14 tit1">
                    <h2 class="gray9">已报名（<?=count($model->user)?>）</h2>
                </div>
                <div id="TryUser" class="clearfix row">
                    <?=$try_users?>
                </div>
                <!--评论-->
                <?=\frontend\modules\club\widgets\Comment::widget(['type'=>'try','type_id'=>$model->id,'route'=>\yii\helpers\Url::to(['/club/comment/index'])])?>
            </div>
        </div>
        <div class="col-s">
            <div class="bd p10 mb10">
                <div class="tc bd_dashB">
                    <p class="lh150 f14">微信扫一扫，分享给好友</p>
                    <p><img alt="" src="<?=\yii\helpers\Url::to(['/club/qrcode/index','data'=>'http://m.365jiarun.com/club-try/detail?id='.$model->id])?>" width="98" height="98" class="db bc"></p>
                </div>
                <?php if($user_invite&&$user_invite->log){ ?>
                <div class="pt10 pb5 bd_dashB">
                    成功邀请人员名单（<i class="red fb"><?=$user_invite&&$user_invite->log?count($user_invite->log):0?></i>）
                </div>
                <ul class="avalist pt15 whbg clearfix">
                    <?php foreach($user_invite->log as $user){ ?>
                    <li class="pw33">
                        <a href="javascript:;" class="db ava sava"><img src="<?=Image::resize($user->customer->photo,45,45)?>" alt="ava" class="w"></a>
                        <p class="tc mt3 ml5 w mr5 oh"><?=$user->customer->nickname?></p>
                    </li>
                    <?php } ?>
                </ul>
                <?php }else{ ?>
                    <p class="tc p10 red"> 您还没有邀请，赶快邀请吧！</p>
                <?php } ?>
            </div>
            <div class="bd mb10">
                <div class="graybg p10"><i class="f14 fb">中奖名单</i></div>
                <div id="TryUserGet">
                    <?=$try_users_get?>
                </div>
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
$(".TryApplyBtn").click(function(){
$.get('/club/try/get-address',function(data){
var obj = $.parseJSON(data);
if(obj.status==0){
alert(obj.message);
}else{
$(".detailCon1").hide();
$(".detailCon1").after(obj.data);
}
});
});
$("body").on('click','.SubmitTryBtn',function(){
$.post('/club/try/submit',{'address_id':$('input[name="address_id"]:checked').val(),'id':<?=$model->id?>},function(data){
var obj = $.parseJSON(data);
if(obj.status==0){
alert(obj.message);
}else{
$(".detailCon2").remove();
$(".detailCon1").show();
alert(obj.message);
window.location.reload();
}
});
});
show_time();
$(".countdown input").focus(function() {
$(this).blur();
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
<?php $this->beginBlock('JS_END') ?>
function show_time(){
var time_start = new Date().getTime(); //设定当前时间
var time_end =  new Date('<?=date('Y/m/d H:i:s',strtotime($model->end_datetime))?>').getTime(); //设定目标时间
// 计算时间差
var time_distance = time_end - time_start;
// 天
var int_day = Math.floor(time_distance/86400000)
time_distance -= int_day * 86400000;
// 时
var int_hour = Math.floor(time_distance/3600000)
time_distance -= int_hour * 3600000;
// 分
var int_minute = Math.floor(time_distance/60000)
time_distance -= int_minute * 60000;
// 秒
var int_second = Math.floor(time_distance/1000)
// 时分秒为单数时、前面加零
if(int_day < 10){
int_day = int_day<0 ? int_day : "0" + int_day;
}
if(int_hour < 10){
int_hour = "0" + int_hour;
}
if(int_minute < 10){
int_minute = "0" + int_minute;
}
if(int_second < 10){
int_second = "0" + int_second;
}
// 显示时间
$("#time_d").val(int_day);
$("#time_h").val(int_hour);
$("#time_m").val(int_minute);
$("#time_s").val(int_second);
// 设置定时器
setTimeout("show_time()",1000);
}
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
