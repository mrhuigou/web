<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\ClubTryUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $try->title.' - '.'试吃成员';
$this->params['breadcrumbs'][] = ['label' => '试吃管理', 'url' => ['/try']];
$this->params['breadcrumbs'][] = '试吃成员';
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
                试吃成员 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
<div class="club-try-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel, 'try' => $try]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'try_id',
            'customer_id',
            'shipping_name',
            'shipping_telephone',
            // 'zone_id',
            // 'city_id',
            // 'district_id',
            'address',
            // 'postcode',
            'creat_at',
            'order_id',
            ['attribute'=>'状态','value'=>function($model)
                    {
                        if($model->status == '1'){
                            return '已中奖';
                        }elseif($model->status == '0'){
                            return '未中奖';
                        }
                    }],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>