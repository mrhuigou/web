<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/8
 * Time: 11:55
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
/* @var $this yii\web\View */
$this->title ='申请提现';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<div class="tc p20 f14">
	<img src="<?=\common\component\image\Image::resize(Yii::$app->user->identity->photo,120,120)?>" width="120" height="120" class="img-circle mt30">
	<h3 class="pt5 f16"><?=Yii::$app->user->identity->nickname?></h3>
	<p class="f14 fb tc p10 lh200">可提现收益：<span class="red"><?=floatval(Yii::$app->user->identity->getCommission())?></span>元</p>
	<?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
		'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
		'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
		'errorOptions'=>['class'=>'error db tl']
	],  ]);?>
	<?= $form->field($model, 'amount',["inputOptions"=>['placeholder'=>'输入提现金额,且只能为整数','class' => 'input-text w amount']]) ?>
	<?= $form->field($model, 'verifyCode',['template' => "{input}{error}",'errorOptions'=>['class'=>'error db']])->widget(Captcha::className(), [
		"options"=>['class' => 'input-text w',"maxlength"=>"4","autocomplete"=>"off",'placeholder'=>'验证码'],
		"imageOptions"=>["class"=>"appbtn w-per35 fr"],
		'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{image}</div>",
	]) ?>
	<?= Html::submitButton('确认提现', ['class' => 'btn lbtn greenbtn w mt10', 'name' => 'signup-button']) ?>
	<?php ActiveForm::end(); ?>
    <div class="mt10">
        <a class="btn lbtn graybtn w " href="/user-share/commission" >提现记录</a>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>

<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
<!--浮动购物车-->