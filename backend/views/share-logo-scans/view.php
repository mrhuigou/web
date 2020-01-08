<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ShareLogoScans */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '场景logo内容', 'url' => ['index']];
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

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->share_logo_scans_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->share_logo_scans_id], [
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
            'share_logo_scans_id',
            'title',
            'scan.title',
            'description',
            'type',
            'parameter',
            'logo_url:url',
        ],
    ]) ?>

</div>
