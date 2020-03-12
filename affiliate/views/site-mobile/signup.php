<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = '注册新用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<header class="header w" id="header">
        <a href="javascript:history.back();" class="his-back">返回</a>
        <h2><?= Html::encode($this->title) ?></h2>
        <a href="<?=\yii\helpers\Url::to(['/site/login','redirect'=>Yii::$app->request->get('redirect')])?>" class="right">登录</a>
    </header>
    <section class="login-bodyer">
        <?php if($type=="email") { ?>
            <?php $form = ActiveForm::begin(['id' => 'form-signup','action'=>'/site/signup?type=email','fieldConfig' => [
                'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
                'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
                'errorOptions'=>['class'=>'error db']
            ],  ]);?>
            <?= $form->field($model, 'email',["inputOptions"=>['placeholder'=>'邮箱地址']]) ?>
            <?= $form->field($model, 'password',["inputOptions"=>['placeholder'=>'请输入密码'], 'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fl\" style=\"display:none;\"></a><a href=\"javascript:void(0);\" class=\"pwd-btn right fl \"><span class=\"bg_l\"></span><span class=\"bg_r\"></span><i>隐藏</i></a></p></div>{error}"])->passwordInput() ?>
            <?= $form->field($model, 'verifyCode',['template' => "{input}{error}",'errorOptions'=>['class'=>'error db']])->widget(Captcha::className(), [
                "options"=>['class' => 'input-text w',"maxlength"=>"4","autocomplete"=>"off",'placeholder'=>'验证码'],
                "imageOptions"=>["class"=>"appbtn w-per35 fr"],
                'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{image}</div>",
            ]) ?>

            <?= Html::submitButton('注册新用户', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'signup-button']) ?>
            <div class="pt-15em clearfix">
                <p class="fl">同意并遵守<a  href="/site/about"  class="green">慧生活服务协议</a></p>
                <p class="fr"><a href="<?=\yii\helpers\Url::to(['/site/signup','redirect'=>Yii::$app->request->get('redirect')])?>" class="blue">手机注册</a></p>
            </div>
            <?php ActiveForm::end(); ?>
        <?php } else{ ?>
        <?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
            'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
            'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
            'errorOptions'=>['class'=>'error db']
        ],  ]);?>
        <?= $form->field($model, 'telephone',["inputOptions"=>['placeholder'=>'请输入手机号','class' => 'input-text w telephone']]) ?>
        <?= $form->field($model, 'password',["inputOptions"=>['placeholder'=>'请输入密码'], 'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fl\" style=\"display:none;\"></a><a href=\"javascript:void(0);\" class=\"pwd-btn right fl \"><span class=\"bg_l\"></span><span class=\"bg_r\"></span><i>隐藏</i></a></p></div>{error}"])->passwordInput() ?>
            <?= $form->field($model, 'verifyCode',[
                'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div><button  type='button' class=\"appbtn graybtn w-per35 fr\" name='send-vcode' id='send-vcode'>获取验证码</button></div>{error}",
                 "inputOptions"=>["maxlength"=>"6","autocomplete"=>"off",'placeholder'=>'验证码'],
            ]) ?>
            <p class="red lh150">每日惠购通过语音播报验证码,请你接听来自0532/400/12590开头的来电.</p>
        <?= Html::submitButton('注册新用户', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'signup-button']) ?>
            <div class="pt-15em clearfix">
                <p class="fl">同意并遵守<a href="/site/about" class="green">慧生活服务协议</a></p>
                <p class="fr"><a href="<?=\yii\helpers\Url::to(['/site/signup','type'=>'email','redirect'=>Yii::$app->request->get('redirect')])?>" class="blue">邮箱注册</a></p>
        </div>
        <?php ActiveForm::end(); ?>
        <?php }?>
    </section>