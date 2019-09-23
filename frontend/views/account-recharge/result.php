<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
$this->title = '充值结果';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="" style="min-width:1100px;">
	<div class="w1100 bc ">
		<!--面包屑导航-->
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			'tag' => 'p',
			'options' => ['class' => 'gray6 pb5 pt5'],
			'itemTemplate' => '<a class="f14">{link}</a> > ',
			'activeItemTemplate' => '<a class="f14">{link}</a>',
		]) ?>
		<div class="bc  clearfix simsun">
			<div class="fl w100 mr10 menu-tree">
				<?= frontend\widgets\UserSiderbar::widget() ?>
			</div>
			<div class="fl w990 ">
				<div class="user_center clearfix whitebg ">
					<div class="">
						<div class="whitebg">
							<div class="orderCata clearfix">
								<a href="<?= Url::to(['/account-recharge'], true); ?>">在线充值</a>
								<a href="<?= Url::to(['/account-recharge/card'], true); ?>" class="cur">充值卡充值</a>
							</div>
							<div class="tc p10 mt10">
								<i class="iconfont fb green f50">&#xe635;</i>
								<h1 class=" lh150 fb f14">充值成功</h1>
								<p class="lh150 f14">充值金额：￥<?=$model->value?></p>
							</div>
							<?php  if($model->card_code=='Hisense' && $model->value > 150 && time()<strtotime('2016-09-16') ) {?>
							<div class="graybg tc red lh200 w p10">
								<h2>您的中秋福利《月饼兑换券》已存用您的帐户中。</h2>
								<p>有效期截止至2016年09月17日。</p>
								<p>中秋福利商品已添加到购物车。</p>
								<p>您可提交订单时使用此优惠券折扣商品金额。</p>
							</div>
							<?php }?>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
