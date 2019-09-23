<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\ClubTrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '试吃管理';
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
                控制台 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<div class="club-try-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建试吃', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'title',
            // 'description:ntext',
            // 'product_base_id',
            // 'product_id',
            // 'image',
            'price',
            'quantity',
            'limit_user',
            'begin_datetime',
            'end_datetime',
            // 'click_count',
            // 'like_count',
            // 'comment_count',
            // 'share_count',
            // 'sort_order',
            'status',
            'creat_at',
            'update_at',
            ['attribute'=>'管理','value'=>function($data){
                return Html::a('成员',\yii\helpers\Url::to(['/try-user/index','try_id'=>$data['id']],true)); }, 'format'=>'raw'],
            ['class' => 'yii\grid\ActionColumn','template'=>'{update}'],
        ],
    ]); ?>

</div>
