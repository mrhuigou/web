<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ExpressOrder */

$this->title ='订单详情';
$this->params['breadcrumbs'][] = ['label' => '快递订单管理', 'url' => ['index']];
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
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_6_1" data-toggle="tab">订单详情</a>
            </li>
            <li >
                <a href="#tab_6_2" data-toggle="tab">商品清单</a>
            </li>


        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_6_1">
                <div class="order-view">
                    <?php ?>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            ['label'=>'订单编号','attribute'=>'order_code'],
                            ['label'=>'订单类型','attribute'=>'order_type'],

                            ['label'=>'客户ID','attribute'=>'customer_id'],
                            ['label'=>'联系人','attribute'=>'contact_name'],
                            ['label'=>'收货人电话','attribute'=>'telephone'],
                            ['label'=>'送货地址','attribute'=>'city','value'=>$model->city.' '.$model->district. ' '. $model->address],
//                            'delivery_type',
                            ['label'=>'送货时间','attribute'=>'delivery_time','value'=> $model->delivery_date.' '.$model->delivery_time
                            ],
//                            'delivery_date',
//                            'delivery_time',
                            'total',
                            ['label'=>'备注','attribute'=>'remark'],
                            'remark:ntext',
                            ['label'=>'订单状态','attribute'=>'express_status_id','value'=>$model->status? $model->status->name:'不存在的状态'],
                            ['label'=>'创建时间','attribute'=>'create_at','value'=>date("Y-m-d H:i:s",$model->create_at)],
                            ['label'=>'更新时间','attribute'=>'update_at','value'=>date("Y-m-d H:i:s",$model->update_at)],
                            'send_status',
                            'send_time:datetime',
                        ],
                    ]) ?>

                </div>
            </div>
            <div class="tab-pane fade" id="tab_6_2">
                <div class="payment-view">
                    <?php \yii\widgets\Pjax::begin(); ?>
                    <?php if($model->expressOrderProducts) {?>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>商品编码</th>
                                <th>包装编码</th>
                                <th>名称</th>
                                <th>单位</th>
<!--                                <th>单价</th>-->
                                <th>数量</th>
<!--                                <th>总计</th>-->
<!--                                <th>实付</th>-->
<!--                                <th>退货数量</th>-->
<!--                                <th>优惠</th>-->
<!--                                <th>赠品</th>-->
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            \yii\widgets\ListView::begin([
                                'dataProvider'=>new \yii\data\ActiveDataProvider([
                                    'query' =>\api\models\V1\ExpressOrderProduct::find()->where(['order_id'=>$model->id])->orderBy(['id' => SORT_ASC]),
                                    'pagination'=>['pageSize' => 200]
                                ]),
                                'itemView'=>"_list_product",

                                'emptyText'=>'',
                            ]);?>
                            <?php \yii\widgets\ListView::end();?>
                            </tbody>
                        </table>
                    <?php } ?>
                    <?php \yii\widgets\Pjax::end(); ?>
                </div>
            </div>


        </div>
    </div>
</div>



</div>
