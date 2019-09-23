<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title ='账户充值';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport">
	<div class="pb50">
	<ul class="filter blueFilter two mt5 f14">
		<li class="cur"><a href="<?=Url::to(['/account-recharge'],true);?>">在线充值</a></li>
		<li><a href="<?=Url::to(['/account-recharge/card'],true);?>">充值卡充值</a></li>
	</ul>
	<?php $form = ActiveForm::begin();?>
	<div class="whitebg p10 pt20 pb20 mt2">
		<?= $form->field($model, 'amount',[
				'template' => "{input}<span class=\"vm red\">{error}</span>",
			    "inputOptions"=>["maxlength"=>"16","autocomplete"=>"off",'placeholder'=>'充值金额','class'=>'input linput w'],
			])->label(false) ?>
			<?php if (\Yii::$app->getSession()->getFlash('error')): ?>
		  <p class="help-block help-block-error"><?=\Yii::$app->getSession()->getFlash('error')?></p>
		<?php endif ?>
	</div>
	<div class="tc p10">
		<?= Html::submitButton('提交', ['class' => 'btn lbtn greenbtn w',"id"=> "subBtn"]) ?>
	</div>
	<?php ActiveForm::end(); ?>
	</div>
</section>
<?= h5\widgets\MainMenu::widget(); ?>
<?php $this->beginBlock('JS_END') ?>
$("#subBtn").on('click',function(){
$.showLoading("正在加载");
setTimeout("$.hideLoading();",2000);
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
