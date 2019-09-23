<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品销售统计';
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
                商品销售统计<small>监控、统计、分析</small>
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
        <br>
        <?= GridView::widget([
	        'dataProvider' => $dataProvider,
	        'columns' => [
		         ['class' => 'yii\grid\SerialColumn'],
		        'product_code',
		        'name',
                'category_name',
		        'stock',
               'quantity_total',
                'product_total',
                'pay_total',
                'order_count',
	        ],
        ]); ?>

    </div>
</div>