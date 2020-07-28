<?php
/* @var $this yii\web\View */
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
$this->title = '退货订单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
    <?=\affiliate\widgets\Customizer::widget();?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                退货订单管理 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<div class="order-index">

    <?php  echo $this->render('_rsearch', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['label'=>'昵称',  'attribute' => 'firstname',  'value' => 'firstname' ],
            ['label'=>'电话',  'attribute' => 'telephone',  'value' => 'telephone' ],
            ['label'=>'订单编号',  'attribute' => 'order_no',  'value' => 'order_no' ],
            ['label'=>'退货时间',  'attribute' => 'date_added',  'value' => 'date_added' ],
	        ['label'=>'订单状态',  'attribute' => 'name',  'value'=>'returnStatus.name'],
            ['label'=>'订单总额',  'attribute' => 'total',  'value' => function($data){
                return number_format($data->total, 2);
            }],
	        ['label'=>'收货电话',  'attribute' => 'shipping_telephone',  'value' => 'orderShipping.shipping_telephone'],
        ],
    ]); ?>
</div>
</div>