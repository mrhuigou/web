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
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <br>
    <div style="">
        <?php if($datas_all){
            $labels = [];
            $datasets_data = [];
            foreach ($datas_all as $key => $value){
                $labels[] = $value['date'];
                $datasets_data[] = $value['customer_count'];


            }
            $datasets_data_gd = [];
            $datasets_data_dm = [];
            if($labels){
                foreach ($labels as $v){
                    if($datas_dm){
                        $dm_status = true;
                        foreach ($datas_dm as $dm){
                            if($dm['date'] == $v){
                                $datasets_data_dm[] = $dm['customer_count'];
                                $dm_status = false;
                                break;
                            }
                        }
                        if($dm_status){
                            $datasets_data_dm[] = 0;
                        }

                    }
                    if($datas_gd){
                        $gd_status = true;
                        foreach ($datas_gd as $gd){
                            if($gd['date'] == $v){
                                $datasets_data_gd[] = $gd['customer_count'];
                                $gd_status = false;
                                break;
                            }
                        }
                        if($gd_status){
                            $datasets_data_gd[] = 0;
                        }
                    }
                }
            }


            ?>
    <?= ChartJs::widget([
        'type' => 'line',//line
        'clientOptions'=>[
//            'legend'=>[
//                'display'=> true,
//                'labels'=> ['fontColor'=> 'rgb(255, 99, 132)'],
//                'item'=>['text'=>'1']
//            ],
//            'width'=>400,
//            'height'=>400,
        ],
//        'options' => [
//            'id'=>'myChart',
//            'title'=>'地推图标',
//            'width'=>400,
//            'height'=>400,
//            'style'=>'display:none'
//        ],
        'data' => [
            'labels' => $labels,
            'datasets' => [
                [
                    'label'=> '新增客户数量',
                    'backgroundColor'=> 'rgba(135,206,250,0)',
                    'borderColor'=> 'rgb(135,206,250)',
//                    'backgroundColor'=> 'rgb(0,0,0,0.9)',
//                    'fillColor' => "rgba(120,120,220,1)",
//                    'strokeColor' => "rgba(120,120,220,1)",
//                    'pointColor' => "rgba(220,220,220,1)",
                    'pointStrokeColor' => "#fff",
                    'data' => $datasets_data
                ],
                [
                    'label'=> '地推新增客户数量',
                    'borderColor'=>'rgb(255,0,0)',
                    'backgroundColor'=> 'rgba(135,206,250,0)',
                    'pointStrokeColor' => "#fff",
                    'data' => $datasets_data_gd
                ],
                [
                    'label'=> 'DM单新增客户数量',
//                    'fillColor' => "#A6FFA6",
                    'backgroundColor'=> 'rgba(135,206,250,0)',
                    'borderColor'=> 'rgb(0,128,0)',
//                    'strokeColor' => "#A6FFA6",
//                    'pointColor' => "#A6FFA6",
//                    'pointStrokeColor' => "#fff",
                    'data' => $datasets_data_dm
                ],

            ]
        ]
    ]);
    ?>
        <?php }?>
    </div>
</div>


