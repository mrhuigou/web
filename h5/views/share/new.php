<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/26
 * Time: 15:00
 */
$this->title = '分享有礼';
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="/">
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
	<img src="/assets/images/share/share-banner.jpg" class="w">
	<div class="pt20 pb30 tc whitebg">
		<p class="f24 fb org mt5 mb10 tc">新会员专享福利券</p>
		<?php if($coupons){?>
		<div class="swiper-container pt10 pb10 shareQuan" id="shareQuan">
			<div class="swiper-wrapper">
				<?php foreach($coupons as $value){?>
				<div class="swiper-slide ">
					<div class="green mb5 clearfix">
						<?php if($value->shipping){?>
							<span class="fr m"><i class="f16">￥</i><i class="f24 fb vm-2">8</i></span>
						<?php }else{?>
						<span class="fr m">
							<?php if($value->type=='F'){?>
							<i class="f16">￥</i><i class="f24 fb vm-2"><?=floatval($value->discount)?></i>
							<?php }else{?>
							<i class="f16">折</i><i class="f24 fb vm-2"><?=$value->getRealDiscount()?></i>
							<?php }?>
						</span>
						<?php }?>
						<span class="f16 oh"><?=$value->type=='F'?"现金券":"折扣券"?></span>
					</div>
					<span class="gray6"><?=$value->description?></span>
					<i class="iconfont">&#xe698;</i>
				</div>
				<?php }?>
			</div>
			<p class="f14 fb  p10 mt5 tc ">分享给新朋友，还有更多惊喜！</p>
			<div class="tc "><a class="btn mbtn orgbtn share-guide" href="javascript:;">立即分享</a></div>
		</div>
		<?php }?>
		<div class="tc">
			<span class="share-weixin">
				<img src="/assets/images/wx.jpg" width="130" height="130px">
			</span>
			<p class="mt10">关注公众号，了解更多...</p>
		</div>
	</div>
	<div class="f0bg tc pt15 pb20">
		<p class="gray6 mt5">客服电话：4008-556-977</p>
	</div>
<?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/zhqd/logo_300x300.jpeg';
}else{
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/logo_300x300.png';
}?>
<?=\h5\widgets\Tools\Share::widget([
	'data'=>[
		'title' =>'终于等到你，小鲜肉们大礼包!',
		'desc' => '新鲜酸奶全场半价，上午下单下午到。',
		'link' => \yii\helpers\Url::to(['/share/index', 'share_user_id' => Yii::$app->user->getId(),'redirect'=>'/site/index'], true),
		'image' => $share_image
	]
])?>
<?php $this->beginBlock("JS") ?>
//滑动
var selectItems = new Swiper('#shareQuan', {
slidesPerView: "auto"
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS'], \yii\web\View::POS_READY);
?>
