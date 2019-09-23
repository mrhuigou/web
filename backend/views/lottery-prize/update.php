<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\V1\LotteryPrize */

$this->title = 'Update Lottery Prize: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lottery Prizes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
