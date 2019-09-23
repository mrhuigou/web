<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/26
 * Time: 10:28
 */
$this->title = '关注公众号';
?>
<?=\h5\widgets\Header::widget(['title'=>'关注公众号'])?>
<div class="activity-1">
    <a href="/"><img src="/assets/images/share/share-banner.jpg" class="w"></a>
    <div class="pl10 pr10 pb10">
        <div class="br5 opc-f p15 mb10 bc">
            <p class="f20 fb tc">您好，新朋友</p>
            <p class="f24 fb org mt5 mb20 tc">免费领取新会员大礼包</p>
	        <?php foreach ($coupons as $coupon){?>
                <div class="br5 bg-wh mb10 activity-1-coupon">
                    <div class="flex-col bd-d-b">
                        <div class="flex-item-7">
                            <h3 class="red pt5 mt1"><?=$coupon->coupon->name?></h3>
                            <span class="f14"><?=$coupon->coupon->name?></span>
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
                        <span class="gray9 tl">有效期：3天</span>
                    </div>
                </div>
	        <?php } ?>
            <p class="w tc">
                <span class="share-weixin">
			<img src="/images/subcription.png" width="130" height="130px" >
		    </span>
            </p>

            <p class="mt10 tc">长按识别二维码领取</p>
            <a href="/" class="btn btn-l btn-red w mt10">返回继续购物</a>
        </div>
        <div class="br5 opc-f p10">
            <!-- 活动规则 -->
            <div class="tit-- mt15 mb10">活动规则</div>
            <ul class="ul ul-decimal ml25 f14">
                <li>红包仅限家润自营商品使用</li>
                <li>每个用户只能领取一次</li>
                <li>其他未尽事宜，请咨询客服</li>
            </ul>
        </div>
    </div>
</div>
<?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/zhqd/logo_300x300.jpeg';
}else{
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/logo_300x300.png';
}?>
<?=\h5\widgets\Tools\Share::widget([
	'data'=>[
		'title' => '新会员红包点击领取',
		'desc' => "物美价廉，当天订单，当天送。",
		'link' => Yii::$app->request->getAbsoluteUrl(),
		'image' => $share_image
	]
])?>
