<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CMS 页面';
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
                <?= Html::encode($this->title) ?> <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

<div class="page-index">
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'page_id',
            'description.title',
            'type',
            'sort_order',
            ['attribute'=>'状态','value'=>function($model)
                    {
                        if($model->status == '1'){
                            return '启用';
                        }elseif($model->status == '0'){
                            return '禁用';
                        }
                    }],
            'date_added',
            ['class' => 'yii\grid\ActionColumn','template'=>"{update} {delete}"],
        ],
    ]); ?>

</div>
</div>