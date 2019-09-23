<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ReturnBase */

$this->title = '编辑评论信息: ' . ' ' . $model->order_id;
$this->params['breadcrumbs'][] = ['label' => '评论管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_id, 'url' => ['view', 'id' => $model->review_id]];
$this->params['breadcrumbs'][] = '编辑';
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
            订单管理 <small>监控、统计、分析</small>
        </h3>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="tabbable">

    <div class="tab-content">
        <div class="tab-pane active" id="tab_6_1">
            <div class="return-base-update">

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

            </div>
        </div>
        <div class="tab-pane fade" id="tab_6_4">
            <div class="products-view">
                <?php if(!is_null($provider)){ ?>
                    <?= GridView::widget([
                        'dataProvider' => $provider,
                        'columns' => [
                            'review_id',
                            'author',
                            'customer_id',
                            'date_added',
                            'text',
                            'rating',
                            'service',
                            'delivery',
                            'status',
                            'date_added',
                            'date_modified',

                        ],
                    ]); ?>

                <?php }else{
                    echo '暂无商品信息';
                }?>

            </div>
        </div>
</div>
</div>