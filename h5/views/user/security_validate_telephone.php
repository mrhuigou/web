<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='手机验证';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
    <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>
<section class="login-bodyer">

    <?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]);?>

    <div class="form-group field-securityvalidatetelephoneform-telephone">
        <div class=" fb tl w">
           <p class="w ">验证手机号码： <?php echo \common\component\Helper\Helper::str_mid_replace(Yii::$app->user->getIdentity()->telephone);?></p>
            </div>
    </div>
    <input type="hidden" value="<?php echo Yii::$app->user->getIdentity()->telephone;?>" class="telephone" />
    <?= $form->field($model, 'verifyCode',[
        'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div><a  href=\"javascript:void(0);\" class=\"btn lbtn graybtn w-per40 \" name='send-vcode' id='send-vcode'>获取验证码</a></div>{error}",
        "inputOptions"=>["maxlength"=>"6","autocomplete"=>"off",'placeholder'=>'请输入语音验证码'],
    ])->label("语音验证码") ?>
    <p class="gray9 lh150">每日惠购通过语音播报验证码,请你接听来自0532/400/12590开头的来电.</p>
    <?= Html::submitButton('提交验证', ['class' => 'btn lbtn w greenbtn', 'name' => 'realname-button']) ?>
    <?php ActiveForm::end(); ?>

</section>

