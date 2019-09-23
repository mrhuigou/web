<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ClubTryUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="club-try-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'try_id') ?>
    <?= Html::hiddenInput('ClubTryUserSearch[try_id]',$try?$try->id:$model->try_id)?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'shipping_name') ?>

    <?php // echo $form->field($model, 'shipping_telephone') ?>

    <?php // echo $form->field($model, 'zone_id') ?>

    <?php // echo $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'district_id') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'postcode') ?>

    <?php // echo $form->field($model, 'creat_at') ?>

    <?php // echo $form->field($model, 'order_id') ?>

     <?php echo $form->field($model, 'status')->dropDownList(['2'=>'全部','0'=>'未中奖','1'=>'已中奖']);?>

     <?php  echo  $form->field($model, 'begin_date')->widget(\yii\widgets\MaskedInput::className(), [
           'mask' => '9999-99-99',
       ]);
     ?>
         <?php  echo  $form->field($model, 'end_date')->widget(\yii\widgets\MaskedInput::className(), [
           'mask' => '9999-99-99',
       ]);
     ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
