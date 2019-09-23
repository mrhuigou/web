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


    <div  class="w990 bc pt50 clearfix">
        <!--左侧：图片区-->
        <div class="login-img fl mt5">
            <a href="#" target="_blank" class="fr">
                <img src="/assets/images/login.jpg" class="bd" height="326">
            </a>
        </div>

        <!--右侧：表单-->
        <div class="login-form mt5 ml10 fl">
            <div class="login-box">
                <!--信息提示-->
                <h3 class="login-tit" style="font-family: simsun;">
                    <span>账户密码登录</span>
                </h3>
                <?php $form = ActiveForm::begin(['id' => 'login-form','fieldConfig' => [
                'template'=>' <p class="fb pt2">{label}：{error}</p>{input}','errorOptions'=>['tag'=>'span','class'=>'fn red']]]); ?>
                <?php if(Yii::$app->getSession()->getFlash('error')){?>
                <div class="login-msg">
                    <p class="error mb5">
                    <?= Yii::$app->getSession()->getFlash('error'); ?>
                    </p>
                </div>
                <?php } ?>
   
                <?= $form->field($model, 'username',['inputOptions' =>['class' => 'login-text']]); ?>
                               
                <?= $form->field($model, 'password', ['template'=>'<p class="pt5"><span class="fb pt2">{label}：</span>{error}</p>{input}','inputOptions' =>['class' => 'login-text']])->passwordInput(); ?>

                <?= $form->field($model, 'verifyCode',['template' => "<p class=\"fb pt2\">{label}：{error}</p>{input}",'inputOptions' =>['class' => 'login-text']])->widget(Captcha::className(), [ "options"=>['class' => 'login-text s',"maxlength"=>"4","autocomplete"=>"off"],
                    "imageOptions"=>["class"=>" w-per35 fr","style"=>"width:75px;height:25px;"],
                    'template' => '{image}{input}',
                ]) ?>
                <?= $form->field($model, 'rememberMe',['options'=>['class'=>'mt5']])->checkbox(); ?>
                <?= Html::submitButton('登录', ['class' => 'mbtn greenbtn tc login-submit mt5', 'name' => 'login-button']) ?>
                <p class="pt5">
                <?= Html::a('忘记登录密码', ['site/request-password-reset'],['class' => 'blue mr10 fr']) ?>
                <?= Html::a('免费注册', ['site/signup'],['class' => 'blue']) ?>
                </p>

               <?php ActiveForm::end(); ?>

<style>
    .auth-title{display: none !important;}
    </style>
            <?=yii\authclient\widgets\AuthChoice::widget([
                'baseAuthUrl' => ['site/auth'],
                'autoRender'=>true,
                'popupMode' => false,
            ])?>
            </div>


        </div>
    </div>

    
