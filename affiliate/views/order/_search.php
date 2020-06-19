<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\AffiliateTransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="affiliate-transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'order_no') ?>
    <?php  echo  $form->field($model, 'begin_date')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ]);
    ?>
        <?php  echo  $form->field($model, 'end_date')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '9999-99-99',
      ]);
    ?>
    <?= $form->field($model, 'commission') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <?php if(Yii::$app->request->getQueryParams()){?>
            <?= Html::a('导出', array_merge(['export'],Yii::$app->request->getQueryParams()), ['class' => 'btn btn-success']) ?>
            <?= Html::a('生成结算单', array_merge(['statement'],Yii::$app->request->getQueryParams()), ['class' => 'btn btn-success']) ?>
        <?php }?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
