<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\CustomerStatisticsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'telephone') ?>

    <?= $form->field($model, 'firstname') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'nickname') ?>

    <?php // echo $form->field($model, 'onmobile') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'email_validate') ?>

    <?php // echo $form->field($model, 'telephone') ?>

    <?php // echo $form->field($model, 'telephone_validate') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'zone_id') ?>

    <?php // echo $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'district_id') ?>

    <?php // echo $form->field($model, 'education') ?>

    <?php // echo $form->field($model, 'occupation') ?>

    <?php // echo $form->field($model, 'idcard') ?>

    <?php // echo $form->field($model, 'idcard_validate') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'salt') ?>

    <?php // echo $form->field($model, 'cart') ?>

    <?php // echo $form->field($model, 'wishlist') ?>

    <?php // echo $form->field($model, 'newsletter') ?>

    <?php // echo $form->field($model, 'address_id') ?>

    <?php // echo $form->field($model, 'customer_group_id') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'approved') ?>

    <?php // echo $form->field($model, 'token') ?>

    <?php // echo $form->field($model, 'date_added') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'longitude') ?>

    <?php // echo $form->field($model, 'latitude') ?>

    <?php // echo $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'forget_link_validity') ?>

    <?php // echo $form->field($model, 'paymentpwd') ?>

    <?php // echo $form->field($model, 'psalt') ?>

    <?php // echo $form->field($model, 'favourite_stores') ?>

    <?php // echo $form->field($model, 'total_follow') ?>

    <?php // echo $form->field($model, 'total_follower') ?>

    <?php // echo $form->field($model, 'total_exp') ?>

    <?php // echo $form->field($model, 'total_comment') ?>

    <?php // echo $form->field($model, 'total_album') ?>

    <?php // echo $form->field($model, 'total_favorite_shares') ?>

    <?php // echo $form->field($model, 'total_favorite_albums') ?>

    <?php // echo $form->field($model, 'is_star') ?>

    <?php // echo $form->field($model, 'usergroup_id') ?>

    <?php // echo $form->field($model, 'credits') ?>

    <?php // echo $form->field($model, 'ext_credits_1') ?>

    <?php // echo $form->field($model, 'ext_credits_2') ?>

    <?php // echo $form->field($model, 'ext_credits_3') ?>

    <?php // echo $form->field($model, 'points') ?>

    <?php // echo $form->field($model, 'customer_level_id') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'signature') ?>

    <?php // echo $form->field($model, 'timeline_bg') ?>

    <?php // echo $form->field($model, 'custom') ?>

    <?php // echo $form->field($model, 'source_from') ?>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'authen_business') ?>

    <?php // echo $form->field($model, 'company_name') ?>

    <?php // echo $form->field($model, 'company_no') ?>

    <?php // echo $form->field($model, 'legel_name') ?>

    <?php // echo $form->field($model, 'auth_key') ?>

    <?php // echo $form->field($model, 'affiliate_id') ?>

    <?php // echo $form->field($model, 'user_agent') ?>

    <?php // echo $form->field($model, 'can_use_cod') ?>

    <div class="form-actions top fluid ">
        <div class="col-md-offset-1 col-md-9" style="margin-left: 90px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
