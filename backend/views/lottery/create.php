<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\V1\Lottery */

$this->title = 'Create Lottery';
$this->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
