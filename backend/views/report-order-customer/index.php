<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单分析';
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
                订单统计<small>监控、统计、分析</small>
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
		        // ['class' => 'yii\grid\SerialColumn'],
                ['label'=>'用户ID','value'=>'customer_id'],
//                ['label'=>'手机','value'=>'customer_telephone'],
		        ['label'=>'下单手机','value'=>'telephone'],
		        ['label'=>'下单数','value'=>'order_count'],
		        ['label'=>'最后下单时间','value'=>'last_date'],
	        ],
        ]); ?>

    </div>
</div>