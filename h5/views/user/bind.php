<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
/* @var $this yii\web\View */
$this->title ='帐户绑定';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
    <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>
<section class="login-bodyer">
    <p class="bd p5 tc red whitebg">微信账户一旦绑定登陆帐户将无法取消，请慎重操作！</p>
    <?php $form = ActiveForm::begin(['id' => 'login-form','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]); ?>
    <?= $form->field($model, 'username',["inputOptions"=>['placeholder'=>'手机号/邮箱']]) ?>
    <?= $form->field($model, 'password',["inputOptions"=>['placeholder'=>'登录密码'], 'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fl\" style=\"display:none;\"></a><a href=\"javascript:void(0);\" class=\"pwd-btn right fl \"><span class=\"bg_l\"></span><span class=\"bg_r\"></span><i>隐藏</i></a></p></div>{error}"])->passwordInput() ?>
    <?= $form->field($model, 'verifyCode',['template' => "{input}{error}",'errorOptions'=>['class'=>'error db']])->widget(Captcha::className(), [
        "options"=>['class' => 'input-text w',"maxlength"=>"4","autocomplete"=>"off",'placeholder'=>'请输入图片验证码'],
        "imageOptions"=>["class"=>"appbtn w-per35 fr"],
        'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{image}</div>",
    ]) ?>
    <?= Html::submitButton('立即绑定', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'login-button']) ?>
    <?php ActiveForm::end(); ?>
    <div style="color:#999;margin:1em 0">
        如果你忘记密码，你可以 <?= Html::a('找回密码', ['site/request-password-reset'],['class'=>'red']) ?>.
    </div>
</section>

