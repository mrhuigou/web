<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\GroundPushStockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '地推点库存管理';
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
<div class="ground-push-stock-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ground Push Stock', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['attribute'=>'ground_push_point_id','value'=>function($model){
                $point = $model->point;
                if($point){
                    return $point->name;
                }
            }],
            ['attribute'=>'product_code','value'=>function($model){
                $product = $model->product;
                if($product){
                    return $product->description->name.'['.$product->product_code.']';
                }
            }],

            ['attribute'=>'qty','label'=>'总库存'],
            ['attribute'=>'tmp_qty','label'=>'占用库存'],
            ['attribute'=>'quantity','label'=>'可用库存','value'=>function($model){
                return $model->quantity;
            }],
            // 'last_time',
            // 'version',

            ['class' => 'yii\grid\ActionColumn','template' => '{view}{update}'
            ],
        ],
    ]); ?>
</div>
</div>