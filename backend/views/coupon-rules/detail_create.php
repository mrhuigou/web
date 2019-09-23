<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\V1\LotteryPrize */

$this->title = '创建详情';
$this->params['breadcrumbs'][] = ['label' => '优惠券组活动', 'url' => ['/coupon-rules/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('detail_form', [
        'model' => $model,
    ]) ?>

</div>
