<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\V1\RechargeCard */

$this->title = 'Update Recharge Card: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Recharge Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="recharge-card-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
