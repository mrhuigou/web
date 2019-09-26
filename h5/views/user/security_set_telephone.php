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
    <?= $form->field($model, 'telephone',["inputOptions"=>['placeholder'=>'请输入手机号','class' => 'input-text w telephone']])->label("手机号码") ?>
    <?= $form->field($model, 'verifyCode',[
        'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div><a   href=\"javascript:void(0);\" class=\"btn lbtn graybtn w-per40 f12\"  id='send-vcode'>获取验证码</a></div>{error}",
        "inputOptions"=>["maxlength"=>"6","autocomplete"=>"off",'placeholder'=>'请输入语音验证码'],
    ])->label("语音验证码") ?>
    <p class="gray9 pt15 pb5">每日惠购通过语音播报验证码,请你接听来自0532/400/12590开头的来电.</p>
    <a class="btn lbtn w greenbtn" id="submitbtn" href="javascript:void()" >确认提交</a>


    <?php ActiveForm::end(); ?>
</section>
<script>
<?php $this->beginBlock("JS") ?>
    $("#submitbtn").click(function () {
        $.showLoading();
        var redirect = '<?php echo Yii::$app->request->get('redirect')?>';
        $.post('/user/ajax-security-set-telephone?redirect='+redirect,{'SecuritySetTelephoneForm[telephone]': $("#securitysettelephoneform-telephone").val(),'SecuritySetTelephoneForm[verifyCode]': $("#securitysettelephoneform-verifycode").val(),'_csrf':'<?php echo Yii::$app->request->csrfToken?>'},
            function(data){
                $.hideLoading();
                if(data.error_code == 'tel_exist'){
                    $.modal({
                        title: "该手机已经存在",
                        text: "该手机号码已经存在，您是否需要绑定该账号？",
                        buttons: [
                            { text: "确定", onClick: function(){
                                $.post('/user/ajax-security-bind-user',{'validate_code':data.validate_code,'redirect_url':data.redirect_url,'_csrf':'<?php echo Yii::$app->request->csrfToken;?>'},function (bind_data) {
                                    $.alert(bind_data.msg);
                                    if(bind_data.status){
                                        window.location.href = bind_data.redirect;
                                    }
                                },'json');
                            } },
                            { text: "取消", onClick: function(){  $.closeModal(); } },
                        ]
                    });
                }
                if(data.error_code == 'code_error'){
                    $.modal({
                        title: "验证码错误",
                        text: "验证码错误，请重新输入",
                        buttons: [
                            { text: "确定", onClick: function(){  $.closeModal(); } },
                        ]
                    });
                }
                if(data.error_code == '0'){
                    $.alert( data.message);
                    window.location.href = data.redirect_url;
                }
            }
        ,'json');
        //$("#form-signup").submit();
    });
<?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS'], \yii\web\View::POS_READY);
?>
