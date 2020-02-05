<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\CustomerStatisticsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">

    <!-- BEGIN STYLE CUSTOMIZER -->
    <?=\backend\widgets\Customizer::widget();?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                <?= Html::encode($this->title) ?> <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'customer_id',
//            'store_id',
            'firstname',
//            'lastname',
//            'nickname',
            // 'onmobile',
            // 'email:email',
            // 'email_validate:email',
             'telephone',
            // 'telephone_validate',
             'gender',
            // 'birthday',
            // 'zone_id',
            // 'city_id',
            // 'district_id',
            // 'education',
            // 'occupation',
            // 'idcard',
            // 'idcard_validate',
            // 'fax',
            // 'password',
            // 'salt',
            // 'cart:ntext',
            // 'wishlist:ntext',
            // 'newsletter',
            // 'address_id',
            // 'customer_group_id',
            // 'ip',
            // 'status',
            // 'approved',
            // 'token',
             'date_added',
            // 'code',
            // 'longitude',
            // 'latitude',
            // 'password_reset_token',
            // 'forget_link_validity',
            // 'paymentpwd',
            // 'psalt',
            // 'favourite_stores:ntext',
            // 'total_follow',
            // 'total_follower',
            // 'total_exp',
            // 'total_comment',
            // 'total_album',
            // 'total_favorite_shares',
            // 'total_favorite_albums',
            // 'is_star',
            // 'usergroup_id',
            // 'credits',
            // 'ext_credits_1',
            // 'ext_credits_2',
            // 'ext_credits_3',
            // 'points',
            // 'customer_level_id',
            // 'photo',
            // 'signature',
            // 'timeline_bg',
            // 'custom',
            // 'source_from',
            // 'id',
            // 'authen_business',
            // 'company_name',
            // 'company_no',
            // 'legel_name',
            // 'auth_key',
            // 'affiliate_id',
            // 'user_agent:ntext',
            // 'can_use_cod',
            ['label' => '最后订单时间','value' => 'sent_time'],
            ['label' => '下单总数','value' => 'order_count'],
            ['label' => '同步订单总数','value' => 'sent_order_count'],
            ['label' => '同步订单金额','value' => 'sale_total'],
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
