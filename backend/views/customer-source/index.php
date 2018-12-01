<?php use dosamigos\chartjs\ChartJs;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
?>


<?php
/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '地推客户来源分析';
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
                <?= Html::encode($this->title) ?> <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

    <br>
    <?= ChartJs::widget([
        'type' => 'bar',//line
        'clientOptions'=>[
            'legend'=>[
                'display'=> false,
                'labels'=> ['fontColor'=> 'rgb(255, 99, 132)'],
                'item'=>['text'=>'1']
                ]
        ],
        'options' => [
                'id'=>'myChart',
                'title'=>'地推图标',
            'width'=>400,
            'height'=>400,
        ],
        'data' => [
            'labels' => ["January1", "February", "March", "April", "May", "June", "July"],
            'datasets' => [
                [
                        'label'=> 'Dataset 1',
//                    'fillColor' => "rgba(220,220,220,0.5)",
//                    'strokeColor' => "rgba(220,220,220,1)",
//                    'pointColor' => "rgba(220,220,220,1)",
//                    'pointStrokeColor' => "#fff",
                    'data' => [65, 59, 90, 81, 56, 55, 40]
                ],
                [

//                    'name'=>'2',
//                    'fillColor' => "rgba(151,187,205,0.5)",
//                    'strokeColor' => "rgba(151,187,205,1)",
////                    'pointColor' => "rgba(151,187,205,1)",
//                    'pointStrokeColor' => "#fff",
                    'data' => [28, 48, 40, 19, 26, 27, 10]
                ]
            ]
        ]
    ]);
    ?>
</div>

