<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='手机验证';
?>
<div class="whitebg bdb p20 pt50 pb50" id="form">
    <?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=> 'org db']
    ],  ]);?>
    <table cellpadding="0" cellspacing="0" class="tableP58 w">

        <tbody><tr>
            <td width="30%" valign="top" align="right">手机：</td>
            <td width="37%"><span class=""> <?= $form->field($model, 'telephone',["inputOptions"=>['placeholder'=>'请输入手机号','class' => 'input_big w250 input-text w telephone']]) ?></td>

            <td></td>
        </tr>
        <tr>
            <td align="right">手机验证码：</td>
            <td>

                <?= $form->field($model, 'verifyCode',[
                    'template' => "<div class='pt-15em clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div><button  type='button' class=\"btn btn_big grayBtn vm\" name='send-vcode' id='send-vcode'>获取验证码</button></div>{error}",
                    "inputOptions"=>["maxlength"=>"6","autocomplete"=>"off",'placeholder'=>'请输入语音验证码','class'=>'input_public input_big'],
                ])->label("语音验证码") ?>

            </td>
            <td><div id="verifyTip" style="margin: 0px; padding: 0px; background: transparent;"></div></td>
        </tr>
        <tr>
            <input type="hidden" value="<?php echo Yii::$app->request->csrfToken;?>" class="" name="csrf" />
            <td valign="top" align="right"></td>
            <td>
                <p class="mt10">
                    <?= Html::submitButton('下一步', ['class' => 'btn btn_middle orgBtn',"id"=> "subBtn",'name' => 'realname-button']) ?>
            </td>
            <td></td>
        </tr>
        </tbody></table>
    <?php ActiveForm::end(); ?>

    <?php $this->beginBlock('JS_END') ?>
    $("#send-vcode").on('click',function (e){
    if($(".telephone").length > 0 ){
    var phone_num = $(".telephone").val();
    }else{
    var phone_num = $(".telephone").val();
    }
    var _csrf = $("input[name='csrf']").val();
    var myreg = /^1[34578]\d{9}$/;
    if(!myreg.test(phone_num)){
    alert('请输入正确的手机号');
    return false;
    }else{
    time( $("#send-vcode"));
    $.post('/site/sendcode',{telephone:phone_num,_csrf:_csrf});
    }
    });
    <?php $this->endBlock() ?>
    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
    ?>

</div>