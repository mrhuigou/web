<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\LotterySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '优惠券分组管理';
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
            'coupon_rules_id',
            'name',
//            'start_time',
//            'end_time',
            'user_total_limit',
            'img_url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
