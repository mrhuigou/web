<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评论管理';
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
                评论管理 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<div class="return-base-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
         'filterModel' => $searchModel,
        'columns' => [
             ['class' => 'yii\grid\SerialColumn'],
            'order_id',
            'product.description.name',
            'product_code',
            'author',
            'text',
            'rating',
            'service',
            'delivery',
            'status',
            'date_added',
            ['class' => 'yii\grid\ActionColumn','template'=>'{view} {update}{replay}' , 'buttons'=>[
                'replay'=>function($url,$data){
                    return Html::a('<span class="glyphicon">回复</span>', $url, ['title' => '回复'] );

                }
            ],
            ],


        ],
    ]); ?>

</div>
</div>