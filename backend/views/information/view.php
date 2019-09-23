<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\InformationDescription */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Information Descriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="information-description-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'information_id' => $model->information_id, 'language_id' => $model->language_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'information_id' => $model->information_id, 'language_id' => $model->language_id], [
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
            'information_id',
            'language_id',
            'title',
            'description:ntext',
            'type',
            'author',
            'brand',
            'image',
            'date_added',
            'date_modified',
            'meta_keyword:ntext',
            'meta_description:ntext',
        ],
    ]) ?>

</div>
