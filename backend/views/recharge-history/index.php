<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\RechargeHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recharge Historys';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recharge-history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Recharge History', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'customer_id',
            'recharge_card_id',
            'created_at',
            'user_agent',
            // 'recharge_card_info',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
