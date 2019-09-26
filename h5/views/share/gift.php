<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/11/9
 * Time: 11:36
 */
use yii\helpers\Html;
$this->title = '分享有礼';
?>
<style>
    .activity-1-coupon:after,.activity-1-coupon:before{content:"";width:12px;height:12px;border-radius:50%;position:absolute;background-color: #F2F5B4;top:44px;}
</style>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="<?php echo \yii\helpers\Url::to(['/site/index'])?>">
            <i class="aui-icon aui-icon-home green f28"></i>
        </a>
        <div class="flex-item-8 f16">
			<?=\yii\helpers\Html::encode($this->title)?>
        </div>
        <a class="flex-item-2 share-guide" href="javascript:;" >
            <i class="iconfont green f28">&#xe644;</i>
        </a>
    </div>
</header>
<div class="w mt50"></div>
<div class="activity-1" style="background-color: #d3df03;">
       <?=\h5\widgets\Ad\Image::widget(['code'=>'H5-SHARE-DES1'])?>
        <div class="pl10 pr10 pb10 mt10">
            <div class="br5 opc-f p15 mb10">
	            <?php if(!Yii::$app->user->identity->telephone_validate){?>
                    <input type="tel" class="input input-l w mb15" placeholder="请输入您的手机号" id="telephone"/>
                    <div class="clearfix">
                        <input type="tel" class="input input-l pw50 mb15" placeholder="请输入手机验证码" id="check_code"/>
                        <a href="javascript:;" class="btn mbtn graybtn mb15 " id="send_code">获取验证码</a>
                    </div>
                    <a href="javascript:;" class="btn btn-l btn-red w" id="check_telephone">立即领取</a>
	            <?php }else{?>
                    <?php if($my_coupons){ ?>
                    <!-- 券 -->
                   <?php foreach ($my_coupons as $coupon){?>
                    <a class="br5 bg-wh mb10 w db activity-1-coupon" href="<?=\yii\helpers\Url::to(['/coupon/view','id'=>$coupon->coupon_id],true)?>">
                        <div class="flex-col bd-d-b">
                            <div class="flex-item-7">
                                <h3 class="red pt5 mt1"><?=$coupon->coupon->name?></h3>
                                <span class="f14"><?=$coupon->coupon->comment?></span>
                            </div>
                            <div class="flex-item-5 red tr">
                                <?php if($coupon->coupon->type=='F'){?>
                                <span class="f25">￥</span><span class="f40"><?=$coupon->coupon->getRealDiscount()?></span>
                                <?php }else{?>
                                    <span class="f40"><?=$coupon->coupon->getRealDiscount()?></span><span class="f25">折</span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="f14 pt5">
                            <span class="gray9">截止：<?=$coupon->end_time?></span>
                        </div>
                    </a>
                    <?php } ?>
                    <p class="tc f14 mb10">红包已放入账号:<?=Yii::$app->user->identity->telephone?></p>
                    <a href="<?php echo \yii\helpers\Url::to(['/user-coupon/index'])?>" class="btn btn-l btn-red w">立即使用</a>
                    <a href="<?php echo \yii\helpers\Url::to(['/news/list'])?>" class="btn btn-l btn-red w mt10">查看更多活动</a>
                    <?php }else{?>
                        <p class="tc  fb f18 lh200">亲你来晚了，红包已被抢光啦！</p>
                        <a href="/" class="btn btn-l btn-red w mt10">继续购物抢红包</a>
                    <?php } ?>
	            <?php } ?>
            </div>
            <div class="br5 opc-f p10">
                <!-- 看看大家 -->
                <?php if($hongbao->history){?>
                <div class="tit-- mt10">看看大家手气</div>
                <div>
                    <?php foreach ($hongbao->history as $history){?>
                    <div class="flex-col activity-1-list">
                        <div class="flex-item-2 tc">
                            <img src="<?=$history->customer->photo?\common\component\image\Image::resize($history->customer->photo,0,0,9):'/assets/images/defaul.png'?>" alt="头像" width="47" height="47" class="img-circle">
                        </div>
                        <div class="flex-item-6 pl10">
                            <p class="pt5"><?=$history->customer->nickname?></p>
                            <p class="gray6 f12 pt2"><?=date('m-d H:i:s',$history->create_at)?></p>
                        </div>
                        <div class="flex-item-4 tr">
	                        <?=floatval($history->amount)?>元
                        </div>
                    </div>
                    <?php }?>
                </div>
                <?php } ?>
                <!-- 活动规则 -->
                <div class="tit-- mt15 mb10">活动规则</div>
                <ul class="ul ul-decimal ml25 f14">
                    <li>红包新老用户同享，只能邀请他人拆，自己不能拆哟！</li>
                    <li>红包仅限家润自营商品使用</li>
                    <li>每个用户只能领取一次</li>
                    <li>其他未尽事宜，请咨询客服</li>
                </ul>
            </div>

        </div>

    </div>
<?=\h5\widgets\Tools\Share::widget([
	'data'=>[
		'title' =>'嘿！快来帮我拆红包，省钱秘密都在这。',
		'desc' => '酸奶、水果、饮料、日常用品，助力红包，给我加油！！！',
		'link' => \yii\helpers\Url::to(['/share/gift','hongbao_id'=>$hongbao->id],true),
		'image' => 'https://m.mrhuigou.com/images/gift-icon.jpg'
	]
])?>
<?php $this->beginBlock('JS_END') ?>
$("#send_code").click(function(){
if($("#telephone").val().length!=11){
$.alert("手机号码不能为空且为11位");
return;
}
$.showLoading("正在加载");
$.post('<?=\yii\helpers\Url::to('/site/sendcode')?>',{'telephone':$("#telephone").val()},function(data){
$.hideLoading();
time($("#send_code"));
},'json');
});
$("#check_telephone").click(function(){
if($("#telephone").val().length!=11){
$.alert("手机号码不能为空且为11位");
return;
}
if($("#check_code").val().length<=0){
$.alert("手机验证码不正确");
return;
}
$.showLoading("正在加载");
$.post('<?=\yii\helpers\Url::to('/share/ajax-check')?>',{'telephone':$("#telephone").val(),'check_code':$("#check_code").val()},function(data){
$.hideLoading();
if(data.status){
location.href='<?=\yii\helpers\Url::to(['/share/gift','hongbao_id'=>$hongbao->id],true)?>';
}else{
$.alert(data.message);
}
},'json');
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>


