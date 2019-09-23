<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\ClubActivityUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $activity->title.' - '.'活动成员';
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['/activity']];
$this->params['breadcrumbs'][] = '活动成员';
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
                活动成员 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<div class="club-activity-user-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'order_id',
            'order.customer_id',
            'order.firstname',
            'order.telephone',
            'quantity',
            'total',
            ['attribute'=>'状态','value'=>function($model)
                    {

                        if($model->order && $model->order->order_status_id == '2'){
                            return '已报名';
                        }else{
                            return '未报名';
                        }
                    }],
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>