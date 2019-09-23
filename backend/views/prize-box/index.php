<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\PrizeBoxSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\widgets\Breadcrumbs;
$this->title = 'Prize Boxes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
    <?= \backend\widgets\Customizer::widget(); ?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                控制台
                <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
<div class="prize-box-index">
    <p>
        <?= Html::a('Create Prize Box', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'type',
            'coupon.name',
            'coupon.code',
            'coupon.date_start',
            'coupon.date_end',
            'probability',
            'date_start',
             'date_end',
             'status',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>