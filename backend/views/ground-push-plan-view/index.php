<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\GroundPushPlanViewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '地推计划详情管理';
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
<div class="ground-push-plan-view-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>-->
<!--        --><?php // Html::a('Create Ground Push Plan View', ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'code',

            [
                'label'=>'所属地推计划',
                'attribute' => 'ground_push_plan_id',
                'filter' => true, //不显示搜索框
                'format'=>'raw',
                'value' => function($model) {
                        if($model->plan){
                            $avalue =  $model->plan->name .' ('.$model->plan->code .')';
                            $url = \yii\helpers\Url::to(['/ground-push-plan/update','id'=>$model->ground_push_plan_id]);
                            return Html::a($avalue, $url, ['title' => '审核']);

                        }
                }
            ],
            [
                    'attribute'=>'product_code',
                    'label' => '商品CODE'
            ],
            'price',
             'max_buy_qty',
             'sort_order',
             'status',
            [
                    'label'=>'状态',
                'attribute' => 'status',
                'value'=>function($model){
                    if($model){
                        if($model->status == 1){
                            return '启用';
                        }else{
                            return '停用';
                        }
                    }
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>