<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='邮箱验证';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
    <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>
<section class="login-bodyer">
    用于登录或找回密码时我们会将新密码发到您的邮箱。
    <?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]);?>

    <?= $form->field($model, 'email',["inputOptions"=>['placeholder'=>'请输入邮箱','class' => 'input-text w email','id'=>'email']])->label("邮箱地址") ?>
    <?= $form->field($model, 'verifyCode',[
        'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div><button  type='button' class=\"appbtn graybtn w-per35 fr\" name='send-vcode' id='send-vcodeemail'>获取验证码</button></div>{error}",
        "inputOptions"=>["maxlength"=>"6","autocomplete"=>"off",'placeholder'=>'请输入邮件验证码'],
    ])->label("邮件验证码") ?>
    <?= Html::submitButton('邮件验证', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'realname-button']) ?>
    <?php ActiveForm::end(); ?>

</section>
<?php $this->beginBlock("JS_Block")?>
$("#send-vcodeemail").click(function(){
    var email = $("#email").val();
    var _csrf='<?php echo Yii::$app->request->getCsrfToken();?>';
    time( $("#send-vcodeemail"));
    $.post('/site/sendcodemail',{email:email,_csrf:_csrf},function(data){

    });
});
<?php $this->endBlock()?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);