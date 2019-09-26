<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/9/29
 * Time: 11:37
 */
$this->title=\yii\helpers\Html::encode($model->title)."---全城互动";
?>
<div class="w1100 bc">
    <div class="layout grid-m0s5">
        <div class="col-m ">
        <div class="main-w  ">
            <h2 class="f16  p5 "><?=\yii\helpers\Html::encode($model->title)?></h2>
            <div class="bd_dashB clearfix p5 mb10">
                <div class="fl f14">发布时间：<?=date('m月d日 H:i',strtotime($model->creat_at))?></div>
                <div class="fr f14">
                   <span>阅读  <?=$model->click_count?> +</span>
                </div>
            </div>
            <div class="clearfix graybg p10 mb10">
                <div class="fl f14 " style="width: 600px;">
                    <p class="mb10"><i class="iconfont">&#xe61b;</i>  <?=date('m月d日 H:i',strtotime($model->begin_datetime))?>  <?=\common\component\Helper\Datetime::getWeekDay(strtotime($model->begin_datetime))?>  开始  &nbsp;&nbsp;<span class="gray9">(<?=date('m月d日 H:i',strtotime($model->signup_end))?> 报名截止)</span></p>
                    <p class="mb10"><i class="iconfont">&#xe61a;</i>  <?=$model->address?$model->address:'暂未确定'?></p>
                    <p class="mb10"><i class="iconfont">&#xe61c;</i>  已报 <b class="red"><?=$model->tickets?></b> 名额 （<?php if($model->qty) { ?>限 <b class="red"><?=$model->qty?></b> 名额<?php }else{ ?>不限 名额<?php } ?>）</p>
                    <div class="mb10">
                        <?php if($model->status){ ?>
                            <?php if(strtotime($model->signup_end)<time()){?>
                                <a class="btn btn_middle disableBtn graybg2"> 报名结束</a>
                                <?php }else{?>
                            <a class="btn btn_middle greenbtn ApplyBtn"> 我要报名</a>
                                <?php }?>
                        <?php }else{?>
                         <a class="btn btn_middle disableBtn graybg2"> 报名关闭</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="fr bdl mr25 ">
                    <p class="tc lh200 f14 pl20">微信扫一扫，分享给好友</p>
                    <p class="tc"><img alt="" src="<?=\yii\helpers\Url::to(['/club/qrcode/index','data'=>'http//m.mrhuigou.com/club-activity/detail?id='.$model->id])?>" width="98" height="98"></p>
                </div>
            </div>
            <div class="bd_dashB p5 mb10 f14 tit1">
                <h2 class="gray9">活动详情</h2>
            </div>
            <div class="p10 con-detail oh">
                <?=\yii\helpers\Html::decode($model->description)?>
            </div>
            <div class="blue f13 mt30 mb10 clearfix">
					<span class="fr">
						<a class=" like-btn iconfont blue f16" href="javascript:;" data-type="activity" data-type-id="<?=$model->id?>"><?=$model->likeStatus ? "&#xe61f;":"&#xe61e;"?> </a>赞(<?=$model->like_count?>+)
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
                                <span><?=$user->customer->nickname?></span>,
                            <?php }} ?>等觉得很赞！
                    </p>
                </div>
            <?php } ?>
            <!--已报用户-->
            <div class="bd_dashB p5 mt15 mb15 f14 tit1">
                <h2 class="gray9">已报名</h2>
            </div>
            <div id="ActivityUser" class="clearfix row">
                <?=$activity_users?>
            </div>
            <!--评论-->
            <?=\frontend\modules\club\widgets\Comment::widget(['type'=>'activity','type_id'=>$model->id,'route'=>\yii\helpers\Url::to(['/club/comment/index'])])?>
        </div>
            </div>
        <div class="col-s">
            <div class="bd p10 mb10">
                <?php if($model->customer){ ?>
                <div class="clearfix  pb10">
                    <img src="<?=\common\component\image\Image::resize($model->customer->photo,45,45)?>" alt="ava" width="45" height="45" class="mr10 fl">
                    <p class="ml50 pl5">
                        <a href="javascript:;" class="f14 blue"><?=$model->customer->nickname?></a>
                    </p>
                    <p class="ml50 pl5 f12 gray9">
                        发起者
                    </p>
                </div>
                <div class=" pl5 f12 gray9 ">
                    <?=$model->customer->signature?\yii\helpers\Html::encode($model->customer->signature):'这家伙很懒,没有留下签名信息!'?>
                </div>
                <div class="clearfix pt10 tc bdt">
                    <a href="javascript:;" class="fl pw50"><span class="fr grayc">|</span>活动数(<?=count($model->customer->clubActivity)?>) </a>
                    <a href="javascript:;" class="fl pw50">参与人数(<?=$model->customer->clubActivityUserQty?>) </a>
                </div>
                <?php }else{ ?>
                    <div class="clearfix  pb10">
                        <img src="/assets/images/avatar.jpg" alt="ava" width="45" height="45" class="mr10 fl">
                        <p class="ml50 pl5">
                            <a href="javascript:;" class="f14 blue">慧生活官方</a>
                        </p>
                        <p class="ml50 pl5 f14 gray9">
                            发起者
                        </p>
                    </div>
                <?php } ?>
            </div>
        <div class="mt10">
            <?=frontend\modules\club\widgets\WeekdayActivity::widget()?>
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
$(".ApplyBtn").click(function(){
$.post("<?=\yii\helpers\Url::to(['/club/default/apply'],true)?>",{'id':<?=$model->id?>},function(data){
var obj= $.parseJSON(data);
if(obj.status==0){
alert(obj.message);
}else{
layer.open({
type: 1,
title: '报名信息',
fix: false,
area: '500px',
shadeClose: false,
content: obj.data
});
}
});
});
$("body").on('submit','#activityForm',function(){
$.post('<?=\yii\helpers\Url::to(['/club/default/submit'])?>',$(this).serialize(),function(data){
var obj= $.parseJSON(data);
if(obj.status==0){
str=' ';
$.each(obj.message, function() { str=this+str; });
alert(str);
}else{
location.href=obj.redirect;
}
});
return false;
});



$("body").on('click','.coupon',function(){
$(this).siblings().removeClass('cur');
$(this).addClass('cur');
$(this).siblings('#item_id').val($(this).attr('id'));
return false;
});
//~~ 数量控制 ~~
$("body").on('click','.num-add',function(){
var a=$(".num-text").val();
a++;
$(".num-text").val(a);
});

$("body").on('click','.num-lower',function(){
var a=$(".num-text").val();
if(a>1){
a--;
}
$(".num-text").val(a);
});

<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
