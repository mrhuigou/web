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
    <?php echo $form->field($model, 'begin_date')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
        'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]);
    ?>
    <?php echo $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
        'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
