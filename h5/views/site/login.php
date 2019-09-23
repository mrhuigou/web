<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '用户登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    body{background-color:#fff;}
</style>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
    <a href="<?=\yii\helpers\Url::to(['/site/signup','redirect'=>Yii::$app->request->get('redirect')])?>" class="right">注册</a>
</header>
<section class="login-bodyer">
    <?php $form = ActiveForm::begin(['id' => 'login-form','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]); ?>
        <?= $form->field($model, 'username',["inputOptions"=>['placeholder'=>'手机号/邮箱']]) ?>
        <?= $form->field($model, 'password',["inputOptions"=>['placeholder'=>'登录密码'], 'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fl\" style=\"display:none;\"></a><a href=\"javascript:void(0);\" class=\"pwd-btn right fl \"><span class=\"bg_l\"></span><span class=\"bg_r\"></span><i>隐藏</i></a></p></div>{error}"])->passwordInput() ?>
        <?= $form->field($model, 'verifyCode',['template' => "{input}{error}",'errorOptions'=>['class'=>'error db']])->widget(Captcha::className(), [
       "options"=>['class' => 'input-text w',"maxlength"=>"4","autocomplete"=>"off",'placeholder'=>'验证码'],
        "imageOptions"=>["class"=>"appbtn w-per35 fr"],
        'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{image}</div>",
    ]) ?>
    <?= $form->field($model, 'rememberMe')->checkbox(['template'=>"<div class='pr pt-15em'>{input}{label}</div>"]) ?>
    <?= Html::submitButton('立即登录', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'login-button']) ?>
    <?php ActiveForm::end(); ?>
    <div style="color:#999;margin:1em 0">
        如果你忘记密码，你可以 <?= Html::a('找回密码', ['site/request-password-reset','redirect'=>Yii::$app->request->get('redirect')],['class'=>'red']) ?>.
    </div>
    <?php if((strpos(strtolower(Yii::$app->request->getUserAgent()), 'jiaruncustomerapp')==false) || (strpos(strtolower(Yii::$app->request->getUserAgent()), 'zhqdapp')==false)){?>
<!--    <h3 class=" mb10">第三方账号登录</h3>-->
<!--        --><?php //echo yii\authclient\widgets\AuthChoice::widget([
//            'baseAuthUrl' => ['site/auth'],
//            'autoRender'=>true,
//            'popupMode' => false,
//        ])?>
    <?php } ?>
</section>
