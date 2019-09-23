<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\V1\GroundPushStock */

$this->title = '创建地推库存';
$this->params['breadcrumbs'][] = ['label' => '地推库存管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ground-push-stock-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
