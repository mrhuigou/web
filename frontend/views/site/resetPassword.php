<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = "修改密码";
?>
<div  class="w990 bc pt20" style="min-height:500px;">
    <div class="steps">
        <ol>
            <li ><i>1</i><span>选择方式</span></li>
            <li class="active"><i>2</i><span>重置密码</span></li>
            <li><i>3</i><span>找回成功</span></li>
        </ol>
    </div>
    <div class="reg-box bc ">
        <div class="mt15 bc w400 ">
                <?php $form = ActiveForm::begin(['fieldConfig'=>['options'=>['class'=>'p5 clearfix'],'labelOptions'=>['class'=>'db fb w tl '],'errorOptions'=>['tag'=>'p'],'inputOptions' =>['class' => 'input linput w']]]); ?>
                <?= $form->field($model, 'password')->passwordInput();?>
                <?= $form->field($model, 'verifyPassword')->passwordInput();?>
                <div class="p5 tc"><button type="submit" class="btn lbtn greenbtn w">确认提交</button></div>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div id="footer"></div>
