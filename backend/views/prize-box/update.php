<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\V1\PrizeBox */
use yii\widgets\Breadcrumbs;
$this->title = 'Update Prize Box: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Prize Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
    <?= \backend\widgets\Customizer::widget(); ?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                控制台
                <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
<div class="prize-box-update">
        <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>