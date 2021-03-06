<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;


/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\InformationDescriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '地推点管理';
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
                控制台 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <div class="ground-push-point-index">

        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Create Ground Push Point', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'code',
                'name',
                'zone_code',
                'zone_name',
                // 'city_code',
                // 'city_name',
                // 'district_code',
                // 'district_name',
                // 'address',
                // 'contact_name',
                // 'contact_tel',
                // 'status',
                // 'create_at',
                // 'update_at',

                ['class' => 'yii\grid\ActionColumn','template' => '{view}{update}'],
            ],
        ]); ?>

    </div>
</div>
