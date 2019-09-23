<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '运费统计';
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
                运费统计<small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <div class="return-base-index">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>



        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            // 'filterModel' => $searchModel,
            'columns' => [
                // ['class' => 'yii\grid\SerialColumn'],
                ['label'=>'开始日期',  'attribute' => 'date_start',  'value' => 'date_start' ],
                ['label'=>'结束日期',  'attribute' => 'date_end',  'value' => 'date_end' ],
                ['label'=>'订单数',  'attribute' => 'orders',  'value' => 'orders' ],
                ['label'=>'配送方式',  'attribute' => 'title',  'value' => 'title' ],
                ['label'=>'金额',  'attribute' => 'total',  'value' => 'total' ],

            ],
        ]); ?>

    </div>
</div>