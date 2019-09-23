<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ClubTry */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '试吃管理', 'url' => ['index']];
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
            试吃管理 <small>监控、统计、分析</small>
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
            <a href="#tab_6_1" data-toggle="tab">试吃详情</a>
        </li>
        <li class="">
            <a href="#tab_6_2" data-toggle="tab">用户列表</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_6_1">
<div class="club-try-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定要删除吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            // 'description:ntext',
            'product_base_id',
            'product_id',
            'image',
            'price',
            'quantity',
            'limit_user',
            'begin_datetime',
            'end_datetime',
            'click_count',
            'like_count',
            'comment_count',
            'share_count',
            'sort_order',
            'status',
            'creat_at',
            'update_at',
        ],
    ]) ?>

</div>
</div>
<div class="tab-pane fade" id="tab_6_2">
    <div class="users-view">
        <?php if(!is_null($users)){?>
            <?= GridView::widget([
                'dataProvider' => $users,
                'columns' => [
                    'customer_id',
                    'shipping_name',
                    'shipping_telephone',
                    ['attribute'=>'收货地址','value'=>function($model)
                    {
                        if(is_null($model->district_id)){
                            $district_name = '';
                        }else{
                            $district_name = $model->district->name;
                        }
                        return '山东省青岛市'.$district_name.$model->address;
                    }],
                    'creat_at',
                    'order_id',
                    ['attribute'=>'状态','value'=>function($model)
                    {
                        $status = ['已报名','已抽中','未抽中'];
                        return $status[$model->status];
                    }],
                ],
            ]); ?>
        <?php }else{
            echo '暂无用户信息';
        }?>
    </div>
</div>
</div>
</div>


<?php $this->beginBlock("JS_Block")?>
    $(document).ready(function(){
        $(".pagination a").each(function(){
            this.href = this.href+"#tab_6_2";
        });
    });

<?php $this->endBlock()?>

<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);?>