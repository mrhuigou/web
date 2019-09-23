<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Exercise Rules';
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
			<?= \yii\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

    <p>
        <?= Html::a('Create Exercise Rule', ['create','exercise_id'=>Yii::$app->request->get('exercise_id')], ['class' => 'btn btn-success']) ?>
    </p>
  <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'exercise.title',
            'is_subcription',
            'order_days',
            'order_count',
             'order_total',
             'product_datas:ntext',
             'start_time',
             'end_time',
             'status',
	        ['class' => 'yii\grid\ActionColumn','template'=>'{view} {update} {rule}' , 'buttons'=>[
		        'rule'=>function($url,$model,$key){
			        return Html::a('<span class="glyphicon">设置赠品</span>', \yii\helpers\Url::to(['/exercise-rule-coupon/index','exercise_rule_id'=>$model->id]), ['title' => '设置赠品'] );
		        }
	        ],
	        ],
        ],
    ]); ?>
</div>
