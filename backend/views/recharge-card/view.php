<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\RechargeCard */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Recharge Cards', 'url' => ['index']];
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
                <?=$this->title?> <small>监控、统计、分析</small>
            </h3>
            <?= \yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<div class="recharge-card-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
	    'model' => $model,
        'attributes' => [
            'id',
            'card_no',
            'value',
            'card_code',
            'card_pin',
            'start_time',
            'end_time',
            'created_at',
            'status',
        ],
    ]) ?>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider(['query'=>\api\models\V1\RechargeHistory::find()->where(['recharge_card_id'=>$model->id])]),
	    'columns' => [
		    ['class' => 'yii\grid\SerialColumn'],
            'id',
            'customer_id',
            'customer.telephone',
            'recharge_card_id',
            'created_at',
            'user_agent'
        ],
    ]) ?>

</div>
