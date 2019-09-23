<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Exercises';
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
        <?= Html::a('Create Exercise', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'code',
            'title',
            'description:ntext',
            'begin_time',
             'end_time',
             'status',
	        ['class' => 'yii\grid\ActionColumn','template'=>'{view} {update} {rule}' , 'buttons'=>[
		        'rule'=>function($url,$model,$key){
			        return Html::a('<span class="glyphicon">设置规则</span>', \yii\helpers\Url::to(['/exercise-rule/index','exercise_id'=>$model->id]), ['title' => '设置规则'] );
		        }
	        ],
	        ],
        ],
    ]); ?>
</div>
