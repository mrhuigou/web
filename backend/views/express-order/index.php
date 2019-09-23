<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\ExpressOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '发货订单管理';
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
				<?= Html::encode($this->title) ?>
            </h3>
			<?= \yii\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            ['label'=>'订单编号','attribute'=>'order_code'],
            ['label'=>'订单类型','attribute'=>'order_type'],
            ['label'=>'客户信息','attribute'=>'customer_id','format'=>'raw','value'=>function($model){
                $customer = \api\models\V1\Customer::findOne(['customer_id'=>$model->customer_id]);
                return Html::a($customer->nickname ? $customer->nickname : $model->contact_name,['customer/index','CustomerSearch[customer_id]'=>$customer->customer_id]);

            }],

             'telephone',
//             'city',
//             'district',
             'address:ntext',
//             'delivery_type',
//             'delivery_date',
//             'delivery_time',
            ['label'=>'订单总额','attribute'=>'total'],
            ['label'=>'订单备注','attribute'=>'remark'],
            ['label'=>'订单状态','attribute'=>'express_status_id','format'=>'raw','value'=>function($model){
                return $model->status ? $model->status->name : '错误的状态';
            }],
            ['label'=>'创建时间','attribute'=>'create_at','value'=>function($model){
                return date("Y-m-d H:i:s",$model->create_at);
            }],

//             'update_at',
//             'send_status',
//             'send_time:datetime',

            ['class' => 'yii\grid\ActionColumn','template'=>'{view}'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
