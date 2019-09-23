<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '地址管理';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['/customer/index']];
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
    <?php //echo Html::a('Create Customer', ['create'], ['class' => 'btn btn-success']) ?>
    <!-- END PAGE HEADER-->
<div class="address-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
             'address_id',
             'customer_id',
            'firstname',
            'address_1',
            'telephone',
             'postcode',
             'country_id',
             'zone_id',
             'city_id',
             'district_id',
            // 'lng',
            // 'lat',

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
</div>