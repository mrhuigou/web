<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ReturnBase */

$this->title = '编辑退货信息: ' . ' ' . $model->order_id;
$this->params['breadcrumbs'][] = ['label' => '退货管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_id, 'url' => ['view', 'id' => $model->return_id]];
$this->params['breadcrumbs'][] = '编辑';
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
            订单管理 <small>监控、统计、分析</small>
        </h3>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_6_1" data-toggle="tab">退货单详情</a>
        </li>
        <li>
            <a href="#tab_6_4" data-toggle="tab">商品清单</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_6_1">
            <div class="return-base-update">

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

            </div>
        </div>
        <div class="tab-pane fade" id="tab_6_4">
            <div class="products-view">
                <?php if(!is_null($products)){ ?>
                    <?= GridView::widget([
                        'dataProvider' => $products,
                        'columns' => [
                            // 'order_product_id',
                            // 'order_id',
                            // 'order_product_group_id',
                            'product_base_id',
                            'product_base_code',
                            'product.store_id',
                            'product.store_code',
                            'product_id',
                            'product_code',
                            // 'model',
                            'name',
                            'quantity',
                            'total',
                            // 'tax',
                            // 'reward',
                            'unit',
                            'format',
                            // 'from_table',
                            // 'from_id',
                            'opened',
                            'comment',
                            ['class' => 'yii\grid\ActionColumn',
                            'template'=>'{deleteproduct}',
                            'buttons'=>[
                            'deleteproduct'=>function($url,$products){
                                return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, ['title' => '删除'] );
                            }
                            ]
                            ],
                        ],
                    ]); ?>
                    <p style="padding: 0 20px; line-height: 1.8" class="text-right">
                        <b>总计：</b><?=$model->total?>
                    </p>
                <?php }else{
                    echo '暂无商品信息';
                }?>
            <?php $form = ActiveForm::begin(); ?>
                <?php echo Html::dropDownList('product_id',null,ArrayHelper::map($orderproducts,'product_id', 'name'),['class'=>'form-control']);?>
                <br><?= Html::submitButton('添加商品', ['class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>

            <?php $form = ActiveForm::begin(['action'=>['refund/addrecord','id'=>$model->return_id]]); 
                $record_type = [['name'=>'退还运费','value'=>'shipping|退还运费'],
                                ['name'=>'补交运费','value'=>'shipping_fee|补交运费'],
                                ['name'=>'折扣券占用','value'=>'coupon|折扣券占用'],
                                ['name'=>'整单促销占用','value'=>'order_promotion|整单促销占用'],
                                ['name'=>'返还代金券','value'=>'voucher|返还代金券'],
                                ['name'=>'店铺整单促销占用','value'=>'store_promotion|店铺整单促销占用'],
                                ['name'=>'其它','value'=>'other|返还金额'],
                                ];
            ?>
            <br>
                添加记录：<?php echo Html::dropDownList('model',null,ArrayHelper::map($record_type,'value', 'name'), ['class'=>'form-control']);?>
                <br>金额：<?php echo Html::Input('text','total',null, ['class'=>'form-control']);?>
                <br><?= Html::submitButton('添加记录', ['class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>
</div>