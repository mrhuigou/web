<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '退货管理';
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
                退货管理 <small>监控、统计、分析</small>
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

    <p>
        <?= Html::a('创建退货单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'return_id',
             'return_code',
            'order_id',
            // 'order_code',
            // 'order_no',
            // 'date_ordered',
            // 'customer_id',
            'firstname',
            // 'lastname',
            // 'email:email',
            'telephone',
             ['label'=>'退货状态',  'attribute' => 'name',  'value' => 'returnStatus.name' ],
            // 'comment:ntext',
            'total',
            'date_added',
            // 'date_modified',
            ['label'=>'同步到后台','value'=>function($data){
                return $data['send_status']==1?'已同步':'未同步';
            }],
             'is_all_return',

            ['class' => 'yii\grid\ActionColumn','template'=>'{view}'],


        ],
    ]); ?>

</div>
</div>