<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\CustomerFollowerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员粉丝管理';
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
<div class="customer-follower-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Customer Follower', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['label'=>'客户ID','attribute'=>'customer_id'],
            ['label'=>'客户姓名','format'=>'raw','value'=>function($model){
                $customer = \api\models\V1\Customer::findOne(['customer_id'=>$model->customer_id]);
                return Html::a($customer->nickname,\yii\helpers\Url::to(['/customer/index','CustomerSearch[customer_id]'=>$customer->customer_id]));
            }],

            ['label'=>'粉丝ID','attribute'=>'follower_id'],
            ['label'=>'粉丝姓名','format'=>'raw','value'=>function($model){
                $customer = \api\models\V1\Customer::findOne(['customer_id'=>$model->follower_id]);
                return Html::a($customer->nickname,\yii\helpers\Url::to(['/customer/index','CustomerSearch[customer_id]'=>$customer->customer_id]));
            }],
            'status',
            ['label'=>'创建时间','attribute'=>'creat_at','value'=>function($model){
                return date("Y-m-d H:i:s",$model->creat_at);
            }],


//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>

