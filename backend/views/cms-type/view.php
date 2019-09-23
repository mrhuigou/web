<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\CmsType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Cms Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cms_type_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cms_type_id], [
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
            'cms_type_id',
            'name',
            'description',
            'weight',
            'status',
        ],
    ]) ?>

</div>
