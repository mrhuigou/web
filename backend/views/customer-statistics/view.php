<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Customer */

$this->title = $model->customer_id;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->customer_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->customer_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'customer_id',
            'store_id',
            'firstname',
            'lastname',
            'nickname',
            'onmobile',
            'email:email',
            'email_validate:email',
            'telephone',
            'telephone_validate',
            'gender',
            'birthday',
            'zone_id',
            'city_id',
            'district_id',
            'education',
            'occupation',
            'idcard',
            'idcard_validate',
            'fax',
            'password',
            'salt',
            'cart:ntext',
            'wishlist:ntext',
            'newsletter',
            'address_id',
            'customer_group_id',
            'ip',
            'status',
            'approved',
            'token',
            'date_added',
            'code',
            'longitude',
            'latitude',
            'password_reset_token',
            'forget_link_validity',
            'paymentpwd',
            'psalt',
            'favourite_stores:ntext',
            'total_follow',
            'total_follower',
            'total_exp',
            'total_comment',
            'total_album',
            'total_favorite_shares',
            'total_favorite_albums',
            'is_star',
            'usergroup_id',
            'credits',
            'ext_credits_1',
            'ext_credits_2',
            'ext_credits_3',
            'points',
            'customer_level_id',
            'photo',
            'signature',
            'timeline_bg',
            'custom',
            'source_from',
            'id',
            'authen_business',
            'company_name',
            'company_no',
            'legel_name',
            'auth_key',
            'affiliate_id',
            'user_agent:ntext',
            'can_use_cod',
        ],
    ]) ?>

</div>
