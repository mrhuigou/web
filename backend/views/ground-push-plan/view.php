<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model api\models\V1\GroundPushPlan */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '地推计划管理', 'url' => ['index']];
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
<div class="ground-push-plan-view">

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
            'code',
            'name',
            'begin_date_time',
            'end_date_time',
            'shipping_end_time',
            'contact_name',
            'contact_tel',
            'status',
            'create_at',
            'ground_push_point_id',
        ],
    ]) ?>

</div>
</div>
