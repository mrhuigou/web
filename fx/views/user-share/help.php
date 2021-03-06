<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/22
 * Time: 11:41
 */
$this->title="我的分享"
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  pb50">
	<div id="swiper_content" class="w  " >
		<div class="swiper-container" id="swiper-container_banner">
			<div class="swiper-wrapper">
				<?php foreach ($ad_1 as $value){?>
				<div class="swiper-slide" >
					<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
						<img  src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w" >
					</a>
				</div>
				<?php }?>
			</div>
			<!-- Add Pagination -->
			<div class="swiper-pagination swiper-pagination-white swiper-pagination-banner"></div>
		</div>
	</div>

	<div class="flex-col tc  bdt bdb pt10 pb10 bg-wh " >
		<a class="flex-item-6 bdr  " href="<?=\yii\helpers\Url::to(['/user-share/index'])?>">
			<i class="iconfont f30  ">&#xe6a1;</i>
			<p>收益</p>
		</a>
<!--		<a class="flex-item-4 bdr " href="--><?//=\yii\helpers\Url::to(['/user-share/follower'])?><!--">-->
<!--			<i class="iconfont f30 ">&#xe6a8;</i>-->
<!--			<p>粉丝</p>-->
<!--		</a>-->
		<a class="flex-item-6  " href="<?=\yii\helpers\Url::to(['/user-share/order'])?>">
			<i class="iconfont f30 ">&#xe6a7;</i>
			<p>订单</p>
		</a>
	</div>
    <div class="tc lh37   f14 tit-- ">
            分享说明
    </div>
    <div class="p10 f14 lh150">
        <ul>
        <li> 1、申请成为团长后，你只需将分销方案分享推荐给他人，收获他人感谢的同时，挣得属于自己的分享收益。</li>
<!--        <li> 2、所有带有标识<i class="iconfont  red f35" >&#xe6a5;</i>的页面，都可分享，点击<i class="iconfont  red f35" >&#xe6a5;</i>标识按弹出的提示分享即可。</li>-->
        <li> 2、好友通过分享链接关注每日惠购公众号，下单并支付后，团长即可按订单获得收益。</li>
        <li> 3、所得收益在订单完成后可提现到绑定的微信钱包。</li>
        <li> 4、若好友下单后退货，则收益退回。</li>
        </ul>
    </div>
    <div class="fx-bottom fx-convert-tar p5 bdt bg-wh">
        <?php if($from_affiliate_mode = \Yii::$app->session->get('from_affiliate_mode',0)){?>
            <!--        <a class="btn lbtn bluebtn w"  href="--><?php //echo \yii\helpers\Url::to(['/affiliate-plan/index']).'?type_code='.$model->plan_type?><!--" >开始分享</a>-->
            <a class="btn lbtn bluebtn w"  href="<?php echo '/affiliate-plan/index?sourcefrom='.$model->code.'&type_code='.$model->plan_type.'&affiliatemode='.$from_affiliate_mode?>" >开始分享</a>
        <?php }else{?>
            <!--        <a class="btn lbtn bluebtn w"  href="--><?php //echo \yii\helpers\Url::to(['/site/index'])?><!--" >开始分享</a>-->
            <a class="btn lbtn bluebtn w"  href="<?php echo '/site/index?sourcefrom='.$model->code?>" >开始分享</a>
        <?php }?>
        <!--<a class="btn lbtn bluebtn w"  href="--><?php //echo \yii\helpers\Url::to(['/affiliate-plan/index']).'?sourcefrom='.$model->code.'&type_code=DEFAULT'?><!--" >开始分享</a>-->
<!--        <a class="btn lbtn bluebtn w"  href="--><?php //echo '/affiliate-plan/index?sourcefrom='.$model->code.'&type_code='.$model->plan_type?><!--" >开始分享</a>-->
<!--        <a class="btn lbtn greenbtn w m5" href="--><?//=\yii\helpers\Url::to(['/affiliate-plan/index']).'&type_code=DEFAULT'?><!--">开始分享</a>-->
    </div>

</section>
