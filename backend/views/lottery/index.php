<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\LotterySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lotteries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lottery', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'description:ntext',
//            'start_time',
//            'end_time',
            // 'chances_per_customer',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
