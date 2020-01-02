<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='提货券（卡）激活';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<div class="w ">
    <?php $form =ActiveForm::begin(['id' => 'form-address','fieldConfig' => [
        'template' => "<dl><dt>{label}</dt><dd>{input}</dd></dl>{error}",
        'inputOptions' => ["class"=>'appbtn tl w'],
        'errorOptions'=>['class'=>'red mt5 mb5 db']
    ],  ]);?>
    <div class="p10">
    <?= $form->field($model, 'card_code',['inputOptions'=> ["placeholder"=>'请输入券(卡)号']]) ?>
    <?= $form->field($model, 'card_pwd',['inputOptions'=> ["placeholder"=>'请输入密码']]) ?>
    </div>
    <div class="w  p10 ">
		<?= Html::submitButton('确认激活', ['class' => 'btn lbtn greenbtn  w', 'name' => 'save-button','id'=>'subBtn']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<div class="w p10 mt50">
    <a href="<?php echo \yii\helpers\Url::to(['/user-coupon/index-delivery'])?>">
        <img class="w" src="/assets/images/mycoupon.jpg">
    </a>
</div>
<?php $this->beginBlock('JS_END') ?>
$("#subBtn").on('click',function(){
$.showLoading("正在加载");
setTimeout("$.hideLoading();",2000);
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
