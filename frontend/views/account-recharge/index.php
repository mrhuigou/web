<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '账户充值';
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
					<style>
						.help-block {
							display: inline-block;
						}
					</style>

					<div>
						<div class="orderCata clearfix">
							<a href="<?= Url::to(['/account-recharge'], true); ?>" class="cur">在线充值</a>
							<a href="<?= Url::to(['/account-recharge/card'], true); ?>">充值卡充值</a>
						</div>
						<?php if (\Yii::$app->getSession()->getFlash('success')): ?>
							<p class="help-block green"><?= \Yii::$app->getSession()->getFlash('success') ?></p>
						<?php endif ?>
						<?php if (\Yii::$app->getSession()->getFlash('error')): ?>
							<p class="help-block help-block-error"><?= \Yii::$app->getSession()->getFlash('error') ?></p>
						<?php endif ?>
						<div class="p30">
							<?php $form = ActiveForm::begin(); ?>
							<?= $form->field($model, 'amount', [
								'template' => "<span class=\"f14 vm\">充值金额：</span>{input}<span class=\"vm red\">{error}</span>",
								"inputOptions" => ["maxlength" => "16", "autocomplete" => "off", 'placeholder' => '充值金额', 'class' => 'input minput w'],
							])->label(false) ?>
							<div class="p10">
								<?= Html::submitButton('提交', ['class' => 'btn mbtn w greenbtn', "id" => "subBtn", 'name' => 'realname-button']) ?>
							</div>
							<?php ActiveForm::end(); ?>
							<p class="org mb30">友情提示：支持国内主流银行储蓄卡充值，在线支付成功后，充值金额会在1分钟内到账；暂不支持提现。</p>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
