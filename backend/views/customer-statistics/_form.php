<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'store_id')->textInput() ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'onmobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email_validate')->textInput() ?>

    <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telephone_validate')->textInput() ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthday')->textInput() ?>

    <?= $form->field($model, 'zone_id')->textInput() ?>

    <?= $form->field($model, 'city_id')->textInput() ?>

    <?= $form->field($model, 'district_id')->textInput() ?>

    <?= $form->field($model, 'education')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'occupation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcard')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcard_validate')->textInput() ?>

    <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cart')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'wishlist')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'newsletter')->textInput() ?>

    <?= $form->field($model, 'address_id')->textInput() ?>

    <?= $form->field($model, 'customer_group_id')->textInput() ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'approved')->textInput() ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_added')->textInput() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'longitude')->textInput() ?>

    <?= $form->field($model, 'latitude')->textInput() ?>

    <?= $form->field($model, 'forget_link_validity')->textInput() ?>

    <?= $form->field($model, 'paymentpwd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'psalt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'favourite_stores')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'total_follow')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_follower')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_exp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_album')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_favorite_shares')->textInput() ?>

    <?= $form->field($model, 'total_favorite_albums')->textInput() ?>

    <?= $form->field($model, 'is_star')->textInput() ?>

    <?= $form->field($model, 'usergroup_id')->textInput() ?>

    <?= $form->field($model, 'credits')->textInput() ?>

    <?= $form->field($model, 'ext_credits_1')->textInput() ?>

    <?= $form->field($model, 'ext_credits_2')->textInput() ?>

    <?= $form->field($model, 'ext_credits_3')->textInput() ?>

    <?= $form->field($model, 'points')->textInput() ?>

    <?= $form->field($model, 'customer_level_id')->textInput() ?>

    <?= $form->field($model, 'signature')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'timeline_bg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'custom')->textInput() ?>

    <?= $form->field($model, 'source_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'authen_business')->textInput() ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'legel_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'can_use_cod')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
