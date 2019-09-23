<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\V1\LotteryPrize */

$this->title = 'Create Lottery Prize';
$this->params['breadcrumbs'][] = ['label' => 'Lottery Prizes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
