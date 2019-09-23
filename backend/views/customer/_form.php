<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'store_id')->textInput() ?>

    <?php echo $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'onmobile')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'email_validate')->textInput() ?>

    <?php echo $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'telephone_validate')->textInput() ?>

    <?php echo $form->field($model, 'gender')->radioList(['男'=>'男','女'=>'女']) ?>

    <?php echo $form->field($model, 'birthday')->textInput() ?>

    <?php //echo $form->field($model, 'zone_id')->textInput() ?>

    <?php //echo $form->field($model, 'city_id')->textInput() ?>

    <?php //echo $form->field($model, 'district_id')->textInput() ?>

    <?php //echo $form->field($model, 'education')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'occupation')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'idcard')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'can_use_cod')->dropDownList([0=>'不能使用',1=>'可以使用'])->label("货到卡付权限") ?>

    <?php //echo $form->field($model, 'idcard_validate')->textInput() ?>

    <?php //echo $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'salt')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'cart')->textarea(['rows' => 6]) ?>

    <?php //echo $form->field($model, 'wishlist')->textarea(['rows' => 6]) ?>

    <?php //echo $form->field($model, 'newsletter')->textInput() ?>

    <?php //echo $form->field($model, 'address_id')->textInput() ?>

    <?php //echo $form->field($model, 'customer_group_id')->textInput() ?>

    <?php //echo $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'status')->textInput() ?>

    <?php //echo $form->field($model, 'approved')->textInput() ?>

    <?php //echo $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'date_added')->textInput() ?>

    <?php //echo $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'longitude')->textInput() ?>

    <?php //echo $form->field($model, 'latitude')->textInput() ?>

    <?php //echo $form->field($model, 'forget_link_validity')->textInput() ?>

    <?php //echo $form->field($model, 'paymentpwd')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'psalt')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'favourite_stores')->textarea(['rows' => 6]) ?>

    <?php //echo $form->field($model, 'total_follow')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'total_follower')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'total_exp')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'total_comment')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'total_album')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'total_favorite_shares')->textInput() ?>

    <?php //echo $form->field($model, 'total_favorite_albums')->textInput() ?>

    <?php //echo $form->field($model, 'is_star')->textInput() ?>

    <?php //echo $form->field($model, 'usergroup_id')->textInput() ?>

    <?php //echo $form->field($model, 'credits')->textInput() ?>

    <?php //echo $form->field($model, 'ext_credits_1')->textInput() ?>

    <?php //echo $form->field($model, 'ext_credits_2')->textInput() ?>

    <?php //echo $form->field($model, 'ext_credits_3')->textInput() ?>

    <?php //echo $form->field($model, 'points')->textInput() ?>

    <?php //echo $form->field($model, 'customer_level_id')->textInput() ?>

    <?php //echo $form->field($model, 'signature')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'timeline_bg')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'custom')->textInput() ?>

    <?php //echo $form->field($model, 'source_from')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'id')->textInput() ?>

    <?php //echo $form->field($model, 'authen_business')->textInput() ?>

    <?php //echo $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'company_no')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'legel_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '保存' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
