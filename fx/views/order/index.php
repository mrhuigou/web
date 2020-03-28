<?php
/* @var $this yii\web\View */
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
$this->title = '订单管理';
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
                订单管理 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<div class="order-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'order_no',
            'total',
	        ['label'=>'订单状态',  'attribute' => 'name',  'value' => 'orderStatus.name' ],
            ['label'=>'订单佣金',  'value' => function ($model) {
                if(in_array($model->order_status_id,[6,7])){
	                return 0.00;
                }else{
	                return $model->commission;
                }

            }],
            'date_added',
            ['label' => '对账',
	        'value' => function ($model) {
                    if(\api\models\V1\AffiliateTransaction::findOne($model->order_id)){
	                    return '已对帐';
                    }else{
	                    return '未对账';
                    }

	        }
        ],
        ],
    ]); ?>
</div>
</div>