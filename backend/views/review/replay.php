<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;
$this->title = '评论回复: ' . ' ' . $model->review_id;
$this->params['breadcrumbs'][] = ['label' => '评论管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->review_id, 'url' => ['view', 'id' => $model->review_id]];
$this->params['breadcrumbs'][] = '回复';


/* @var $this yii\web\View */
/* @var $model api\models\V1\ReturnBase */
/* @var $form yii\widgets\ActiveForm */
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
                订单管理 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <?php
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'product.description.name',
            'author',
            'text',
            'rating',
            'service',
            'delivery',
            'status'
        ],
    ]);
    ?>

            <?= $this->render('_form_replay', [
                'model' => $model_replay,
            ]) ?>

</div>