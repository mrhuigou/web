<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单统计';
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
                <div class="tabbable" style="margin-top: 10px;">
<!--                    <ul class="nav nav-tabs">-->
<!--                        <li class="active">-->
<!--                            <a href="#tab_6_1" data-toggle="tab">表格</a>-->
<!--                        </li>-->
<!--                        <li >-->
<!--                            <a href="#tab_6_2" data-toggle="tab">图表</a>-->
<!--                        </li>-->
<!---->
<!--                    </ul>-->
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_6_1">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                // ['class' => 'yii\grid\SerialColumn'],
                                ['label' => '日期','value' => 'date'],
                                ['label' => '订单总数','value' => 'orders'],
                                ['label' => '商品订单数','value' =>function($model){
                                 return $model['orders']-$model['recharge_count'];
                                }],
                                ['label' => '充值订单数','value' => 'recharge_count'],
                              //  ['label' => '订单总额','value' => 'total'],
                                ['label' => '商品销售额','value' => 'sale_total'],
                                ['label'=>'客单价','value'=>function($model){
                                    return number_format($model['sale_total']/($model['orders']-$model['recharge_count']),2);
                                }],
                                ['label' => '下单用户数','value' => 'customer_count'],
                                ['label'=>'首次下单用户数','value'=>'firt_order_count'],
                                ['label'=>'注册并下单用户数','value'=>'sign_date_count'],

                            ],
                        ]); ?>
                    </div>

                    <div class="tab-pane" id="tab_6_2">
                        <?php if($datas_all){
                            $labels = [];
                            $datasets_data = [];
                            foreach (array_reverse($datas_all) as $key => $value){
                                $labels[] = $value['date'];
                                $datasets_data['customer_count'][] = intval($value['customer_count']);
                                $datasets_data['orders'][] = intval($value['orders']);
                                $datasets_data['product_orders'][] = $value['orders'] - $value['recharge_count'];
                                $datasets_data['recharge_count'][] = intval($value['recharge_count']);
                                $datasets_data['total'][] = floatval($value['total']);
                                $datasets_data['sale_total'][] = floatval($value['sale_total']);
                                $datasets_data['firt_order_count'][] = intval($value['firt_order_count']);
                                $datasets_data['per_total'][] = number_format($value['total']/$value['orders'],2);
                            }


                            ?>
                            <?= \dosamigos\chartjs\ChartJs::widget([
                                'type' => 'bar',//line
                                'clientOptions'=>[],
                                    'data' => [
                                        'labels' => $labels,
                                        'datasets' => [
                                            [
                                                'label'=> '订单总数',
                                                'backgroundColor'=> 'rgba(150,150,150,0.5)',
//                                                'borderColor'=> 'rgb(135,206,250)',
                                                'pointStrokeColor' => "#fff",
                                                'data' =>  $datasets_data['orders']
                                            ],
                                            [
                                                'label'=> '下单用户数',
                                                'backgroundColor'=> 'rgba(151,187,205,0.5)',
//                                                'borderColor'=> 'rgb(0,128,0)',
                                                'pointStrokeColor' => "#fff",
                                                'data' => $datasets_data['customer_count']
                                            ],
                                            [
                                                'label'=> '首次下单用户',
//                                                'borderColor'=>'rgb(255,0,0)',
                                                'backgroundColor'=> 'rgba(200,120,205,0.5)',
                                                'pointStrokeColor' => "#fff",
                                                'data' =>  $datasets_data['firt_order_count']
                                            ],
//                                        [
//                                            'label'=> '订单总额',
////                                            'backgroundColor'=> 'rgba(135,206,250,0)',
//                                            'borderColor'=> 'rgb(0,128,0)',
//                                            'data' => $datasets_data['total']
//                                        ],

                                        ]
                                    ]

                            ]);
                            ?>
                            <?= \dosamigos\chartjs\ChartJs::widget([
                                'type' => 'line',//line
                                'clientOptions'=>[],
                                'data' => [
                                    'labels' => $labels,
                                    'datasets' => [
                                        [
                                            'label'=> '订单总额',
                                            'backgroundColor'=> 'rgba(135,206,250,0)',
                                            'borderColor'=> 'rgb(135,206,250)',
                                            'pointStrokeColor' => "#fff",
                                            'data' => $datasets_data['total']
                                        ],
                                        [
                                            'label'=> '商品销售额',
                                            'borderColor'=>'rgb(255,0,0)',
                                            'backgroundColor'=> 'rgba(135,206,250,0)',
                                            'pointStrokeColor' => "#fff",
                                            'data' => $datasets_data['sale_total']
                                        ],
                                        [
                                            'label'=> '客单价',
                                            'backgroundColor'=> 'rgba(135,206,250,0)',
                                            'borderColor'=> 'rgb(0,128,0)',
                                            'data' => $datasets_data['per_total']
                                        ],

                                    ]
                                ]

                            ]);
                            ?>
                        <?php }?>
                    </div>
            </div>

        </div>
</div>
