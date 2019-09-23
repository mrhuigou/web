<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='免费试申请';
?>

<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18">申请报名</h2>
</header>

<section class="veiwport">
    <p class="org mt10 mb10">提示：收货地址目前仅限（市南、市北、崂山、李沧）</p>
    <?php if(Yii::$app->getSession()->getFlash('error')){?>
    <p class="error db  tc mb10 fb"><?= Yii::$app->getSession()->getFlash('error')?></p>
    <?php } ?>

    <?php $form = ActiveForm::begin(['id' => 'login-form','fieldConfig' => [
        'template'=>'{input}{error}',
        'inputOptions' => ['class' => 'appbtn tl w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]); ?>
    <div class="colorbar"></div>
    <a class="pt5 pb5 pl15 db pr20 rarrow whitebg f14"  id="shipping_address" href="<?=\yii\helpers\Url::to(['/address/index','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>">
        <?php if($address) { ?>
            <?=$form->field($model,'address_id')->hiddenInput()?>
            <p><em class="fr"><?=$address->telephone?></em>收货人：<?=$address->firstname?></p>
            <p><?=$address->zone?$address->zone->name:""?> <?=$address->citys?$address->citys->name:""?> <?=$address->district?$address->district->name:''?></p>
            <p><?=$address->address_1?></p>
        <?php }else{?>
            <?=$form->field($model,'address_id')->hiddenInput()?>
            <p class="tc p20"><span class="iconfont fb">&#xe60c;</span>选择你的收货地址 </p>
        <?php } ?>
    </a>
    <div class="colorbar mb10"></div>

    <?= $form->field($model, 'code',[
        'template' => "<div class='pt-15em pl5 pr5 clearfix'><div class=\"pr w-per60 fl\">{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div><button  type='button' class=\"appbtn graybtn w-per35 fr\" name='send-sms' id='send-sms'>获取验证码</button></div>{error}",
        "inputOptions"=>["maxlength"=>"6","autocomplete"=>"off",'placeholder'=>'注册手机验证码'],
    ]) ?>

    <?= Html::submitButton('提交报名', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'login-button']) ?>
    <?php ActiveForm::end(); ?>
</section>
<?php
$this->beginBlock('JS_BLOCK')
?>
$("#send-sms").live('click',function (e){
time( $("#send-sms"));
$.post('/site/sendcode',{telephone:<?=Yii::$app->user->identity->telephone?>});
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_BLOCK'],\yii\web\View::POS_READY);
?>
