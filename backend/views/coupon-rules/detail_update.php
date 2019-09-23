<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\V1\LotteryPrize */

$this->title = '更新组详情: ' . $model->coupon_rules_detail_id;
$this->params['breadcrumbs'][] = ['label' => '活动', 'url' => ['/coupon-rules/index']];
$this->params['breadcrumbs'][] = ['label' => $model->coupon_rules_detail_id, 'url' => ['/coupon-rules/view', 'id' => $model->coupon_rules_id]];

?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('detail_form', [
        'model' => $model,
    ]) ?>

</div>
