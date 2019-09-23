<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\V1\RechargeHistory */

$this->title = 'Create Recharge History';
$this->params['breadcrumbs'][] = ['label' => 'Recharge Historys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recharge-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
