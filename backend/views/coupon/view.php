<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ClubTry */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '折扣券', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
            折扣券管理 <small>监控、统计、分析</small>
        </h3>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_6_1" data-toggle="tab">折扣券详情</a>
        </li>
        <!-- <li class="">
            <a href="#tab_6_2" data-toggle="tab">用户列表</a>
        </li> -->
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_6_1">
<div class="coupon-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->coupon_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coupon_id',
            'name',
            'code',
            'type',
            'discount',
            'max_discount',
            'min_discount',
            'limit_min_quantity',
            'limit_max_quantity',
            'total',
            'limit_max_quantity',
            'date_type',
            'date_start',
            'date_end',
            'expire_seconds',
            'shipping',
            'quantity',
            'user_limit',
            'is_entity',
            'is_open',
            'status',
            'date_added',
            'receive_begin_date',
            'receive_end_date',
            'image_url:url',
            'comment',
        ],
    ]) ?>

</div>
</div>
</div>





</div>

</div>

