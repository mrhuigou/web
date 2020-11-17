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
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="/">
            <i class="aui-icon aui-icon-home green f28"></i>
        </a>
        <div class="flex-item-8 f16">
			<?=Html::encode($this->title)?>
        </div>
        <a class="flex-item-2 share-guide" href="javascript:;" >
            <i class="iconfont green f28">&#xe644;</i>
        </a>
    </div>
</header>
<div class="w mt50"></div>
<img src="/assets/images/share/share-banner.jpg" class="w">
<div class="pt20 pb30 whitebg">
	<div class="flex-col share-count">
		<div class="flex-item-4 tc">
			<img class="img-circle" width="85" height="85" src="<?=\common\component\image\Image::resize($hongbao->customer->photo,100,100)?>" alt="头像" />
		</div>
		<div class="flex-item-8">
			<div class="share-ava">
				<h2 class="f18">我是<?=$hongbao->customer->nickname?></h2>
				<p>感谢您为我助力，您获得</p>
			</div>
		</div>
	</div>
	<div class="swiper-container pt20 pb20 shareQuan bc">
		<div class="swiper-wrapper">
			<a class="swiper-slide bc" style="width:50%" href="<?=\yii\helpers\Url::to(['/user-coupon/index'])?>">
				<div class="green mb5">
					<span class="fr m"><i class="f16">￥</i><i class="fb f30  vm-2">8.8</i></span>
					<span class="f16">现金券</span>
				</div>
				<span class="gray6">无门槛使用</span>
				<i class="iconfont">&#xe698;</i>
			</a>
		</div>
	</div>
	<div class="tc">
		<p class="f16 fb">分享给好友大礼包，当TA首次下单时</p>
		<p class="f24 fb org mt5 mb15">您将再获5元现金奖励</p>
		<a class="btn mbtn orgbtn fb share-guide" href="javascript:;">立即分享</a>
		<a class="btn mbtn greenbtn fb " href="/">马上购物</a>
	</div>

</div>

<div class="f0bg tc pt15 pb20">
	<p class="gray6 mt5">客服电话：<?= Yii::$app->common->getSiteMobile() ?></p>
</div>
<?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/zhqd/logo_300x300.jpeg';
}else{
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/logo_300x300.png';
}?>
<?=\h5\widgets\Tools\Share::widget([
	'data'=>[
		'title' =>'新鲜酸奶全场半价，上午下单下午到。下单再返8块8！',
		'desc' => '酸奶水果、酒水饮料、洗化、日常用品，还有更多惊喜，戳！戳！戳！',
		'link' => \yii\helpers\Url::to(['/share/index', 'share_user_id' => Yii::$app->user->getId(),'redirect'=>'/site/index'], true),
		'image' => $share_image
	]
])?>

