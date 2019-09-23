<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\extensions\widgets\fileapi\Widget as FileAPI;
/* @var $this yii\web\View */
/* @var $model api\models\V1\PrizeBox */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prize-box-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'coupon_id')->dropDownList(ArrayHelper::map(\api\models\V1\Coupon::find()->where(['status'=>1,'is_prize'=>1])->orderBy('coupon_id')->orderBy('coupon_id desc')->all(),'coupon_id', 'name','code')) ?>
    <?= $form->field($model, 'type')->dropDownList(['draw'=>'抽奖活动','register'=>'注册礼券']) ?>
    <?= $form->field($model, 'probability')->textInput() ?>

    <?= $form->field($model, 'image')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/prize-box/fileapi-upload']
            ]
        ]
    ) ?>

	 <?= $form->field($model, 'date_start',['options'=>['class'=>'']])->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99 ',
      ]) ?>

        <?= $form->field($model, 'date_end',['options'=>['class'=>'']])->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ]) ?>

    <?= $form->field($model, 'status')->radioList(['禁用','启用']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
