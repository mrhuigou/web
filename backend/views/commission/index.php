<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分销管理';
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
	            <?= Html::encode($this->title) ?>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <p>
		<?= Html::a('创建分销者', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<div class="coupon-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
             'customer_id',
	        ['label' => '头像',
		        'format' => ['image',['width'=>'50', 'height'=>'50']],
		        'value' => function ($model) {
			        return \common\component\image\Image::resize($model->customer->photo,50,50);
		        }
	        ],
            'username',
            'telephone',
            'commission',
            'status',
             'date_added',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>