<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '优惠使用统计';
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
                优惠使用统计<small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <div class="return-base-index">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            // 'filterModel' => $searchModel,
            'columns' => [
                // ['class' => 'yii\grid\SerialColumn'],
                ['label'=>'优惠券码',  'attribute' => 'code',  'value' => 'code' ],
                ['label'=>'名称',  'attribute' => 'name',  'value' => 'name' ],
	            ['label'=>'发放数量',  'attribute' => 'coupon_count',  'value' => 'user_count' ],
                ['label'=>'通知成功数',  'attribute' => 'notice_count',  'value' => 'notice_count' ],
                ['label'=>'通知失败数',  'attribute' => 'notice_fail_count',  'value' => 'notice_fail_count' ],
                ['label'=>'使用数量',  'attribute' => 'coupon_count',  'value' => 'coupon_count' ],
	            ['label'=>'使用人数',  'attribute' => 'customer_count','format'=>'raw',  'value' => function($model){
                    return $model['customer_count'];//.'['.Html::a('导出',['report-sale-coupon/export?type=export_use_customer&coupon_id='.$model['coupon_id']]).']';
                }  ],
                ['label'=>'未使用人数',  'attribute' => 'customer_not_use_count','format'=>'raw',  'value' => function($model){
                    return $model['customer_not_use_count'];//.'['.Html::a('导出',['report-sale-coupon/export?type=export_not_use_customer&coupon_id='.$model['coupon_id']]).']';
                }  ],

	            ['label'=>'订单数',  'attribute' => 'order_count',  'value' => 'order_count' ],
	            ['label'=>'优惠总计',  'attribute' => 'coupon_total',  'value' => 'coupon_total' ],
	            ['label'=>'订单合计',  'attribute' => 'order_total',  'value' => 'order_total' ],
            ],
        ]); ?>

    </div>
</div>