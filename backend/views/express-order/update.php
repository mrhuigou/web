<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ExpressOrder */

$this->title = 'Update Express Order: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Express Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
    <?=\backend\widgets\Customizer::widget();?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                <?= Html::encode($this->title) ?>
            </h3>
            <?= \yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_6_1" data-toggle="tab">订单详情</a>
            </li>
            <li >
                <a href="#tab_6_2" data-toggle="tab">商品清单</a>
            </li>


        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_6_1">
                <div class="order-view">


                </div>
            </div>
            <div class="tab-pane fade" id="tab_6_2">
                <div class="payment-view">

                </div>
            </div>
        <div class="express-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
