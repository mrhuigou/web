<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Lottery */

$this->title = '编辑优惠券组活动管理: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '优惠券组活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->coupon_rules_id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
