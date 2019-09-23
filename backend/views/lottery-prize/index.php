<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lottery Prizes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Lottery Prize', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'lottery_id',
            'title',
            'description:ntext',
            'image',
            // 'quantity',
            // 'probability',
            // 'angle',
            // 'coupon_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
