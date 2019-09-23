<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分销商管理';
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
                分销商管理 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<div class="affiliate-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建分销商', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'affiliate_id',
            'username',
            // 'lastname',
            // 'email:email',
            'telephone',
            // 'fax',
            // 'password',
            // 'salt',
            // 'company',
            // 'website',
            // 'address_1',
            // 'address_2',
            // 'city',
            // 'postcode',
            // 'country_id',
            // 'zone_id',
            'code',
            // 'commission',
            // 'tax',
            // 'payment',
            // 'cheque',
            // 'paypal',
            // 'bank_name',
            // 'bank_branch_number',
            // 'bank_swift_code',
            // 'bank_account_name',
            // 'bank_account_number',
            // 'ip',
            // 'status',
            // 'approved',
            'date_added',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>