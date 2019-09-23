<?php
/* @var $this yii\web\View */
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$this->title = $model->order_id;
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => ['index']];
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
            订单管理 <small>监控、统计、分析</small>
        </h3>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_6_1" data-toggle="tab">订单详情</a>
        </li>
        <li >
            <a href="#tab_6_2" data-toggle="tab">支付信息</a>
        </li>
        <li>
            <a href="#tab_6_3" data-toggle="tab">寄送地址</a>
        </li>
        <li>
            <a href="#tab_6_4" data-toggle="tab">商品清单</a>
        </li>

        <li>
            <a href="#tab_6_5" data-toggle="tab">订单状态</a>
        </li>
        <li>
            <a href="#tab_6_6" data-toggle="tab">订单编辑</a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['order/index','OrderSearch[customer_id]'=>$model->customer_id])?>" >往期订单</a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['customer/view','id'=>$model->customer_id])?>" >会员信息</a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['comment/index','CommentSearch[order_id]'=>$model->order_id])?>" >物流评价</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_6_1">
            <div class="order-view">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                         'order_id',
                         'order_type_code',
                        'order_no',
                        'store_name',
                         'customer_id',
                        'firstname',
                        'email:email',
                        'telephone',
                         'gender',
                        'merge_code',
                        'payment_deal_no',
                        'payment_method',
                         'payment_code',
                        'total',
                        'invoice_temp',
                        'invoice_title',
                        'comment:ntext',
                        'orderStatus.name',
                        'ip',
                        'user_agent',
                         'accept_language',
                        'date_added',
                        'date_modified',
                        'affiliate_id',
                        'commission',
                         'sent_to_erp',
                         'sent_time',
                    ],
                ]) ?>

            </div>
        </div>
        <div class="tab-pane fade" id="tab_6_2">
            <div class="payment-view">
                <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => new \yii\data\ActiveDataProvider([
                            'query' => \api\models\V1\OrderPayment::find()->where(['order_id'=>$model->order_id])->orderBy(['order_payment_id' => SORT_ASC]),
                            'pagination'=>['pageSize' => 5]
                        ]),
                        'columns' => [
                            'payment_deal_no',
                            'payment_method',
                             'payment_code',
                            'total',
                            'remark',
                            'date_added',
                        ],
                    ]) ?>
                <?php Pjax::end(); ?>
            </div>
        </div>

        <div class="tab-pane fade" id="tab_6_3">
            <div class="shipping-view">
                <?php if($model->orderShipping){?>
                    <?= DetailView::widget([
                        'model' => $model->orderShipping,
                        'attributes' => [
                            'shipping_firstname',
                            'shipping_telephone',
                            'shipping_postcode',
                            'shipping_zone',
                            'shipping_city',
                            'shipping_district',
                            'shipping_address_1',
                            'shipping_method',
                             'shipping_code',
                            'delivery_code',
                            'delivery',
                            'delivery_time',
                            'station_id',
                            'station_code',
                             'is_delivery',
                        ],
                    ]) ?>
    <?php }?>
            </div>
        </div>
        <div class="tab-pane fade" id="tab_6_4">
            <?php Pjax::begin(); ?>
            <?php if($model->orderProducts) {?>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>商品编码</th>
                    <th>包装编码</th>
                    <th>名称</th>
                    <th>单位</th>
                    <th>单价</th>
                    <th>数量</th>
                    <th>总计</th>
                    <th>实付</th>
                    <th>退货数量</th>
                    <th>优惠</th>
                    <th>赠品</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
            <?php
            \yii\widgets\ListView::begin([
                'dataProvider'=>new \yii\data\ActiveDataProvider([
                    'query' =>\api\models\V1\OrderProduct::find()->where(['order_id'=>$model->order_id])->orderBy(['order_product_id' => SORT_ASC]),
                    'pagination'=>['pageSize' => 200]
                ]),
                'itemView'=>"_list_product",
                'emptyText'=>'',
            ]);?>
            <?php \yii\widgets\ListView::end();?>
                </tbody>
            </table>
            <?php } ?>
            <?php if($model->orderDigitalProducts) { ?>
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>类型</th>
                        <th>名称</th>
                        <th>充值内容</th>
                        <th>单价</th>
                        <th>数量</th>
                        <th>总计</th>
                        <th>状态</th>
                        <th>备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    \yii\widgets\ListView::begin([
                        'dataProvider'=>new \yii\data\ActiveDataProvider([
                            'query' =>\api\models\V1\OrderDigitalProduct::find()->where(['order_id'=>$model->order_id])->orderBy(['id' => SORT_ASC]),
                            'pagination'=>['pageSize' => 20]
                        ]),
                        'itemView'=>"_list_digital_product",
                        'emptyText'=>'',
                    ]);?>
                    <?php \yii\widgets\ListView::end();?>
                    </tbody>
                </table>
            <?php } ?>
            <?php if($model->activity) { ?>
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>类型</th>
                        <th>名称</th>
                        <th>内容</th>
                        <th>单价</th>
                        <th>数量</th>
                        <th>总计</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    \yii\widgets\ListView::begin([
                        'dataProvider'=>new \yii\data\ActiveDataProvider([
                            'query' =>\api\models\V1\OrderActivity::find()->where(['order_id'=>$model->order_id])->orderBy(['order_activity_id' => SORT_ASC]),
                            'pagination'=>['pageSize' => 20]
                        ]),
                        'itemView'=>"_list_activity",
                        'emptyText'=>'',
                    ]);?>
                    <?php \yii\widgets\ListView::end();?>
                    </tbody>
                </table>
            <?php } ?>
            <?php Pjax::end(); ?>
            <?php Pjax::begin(); ?>
            <?php if($model->orderGifts) {?>
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>商品编码</th>
                        <th>包装编码</th>
                        <th>名称</th>
                        <th>单位</th>
                        <th>单价</th>
                        <th>数量</th>
                        <th>总计</th>
                        <th>退货数量</th>
                        <th>备注</th>
                        <th>来源</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    \yii\widgets\ListView::begin([
                        'dataProvider'=>new \yii\data\ActiveDataProvider([
                            'query' =>\api\models\V1\OrderGift::find()->where(['order_id'=>$model->order_id,'order_product_id'=>0])->orderBy(['order_gift_id' => SORT_ASC]),
                            'pagination'=>['pageSize' => 20]
                        ]),
                        'itemView'=>"_list_order_gift",
                        'emptyText'=>'',
                    ]);?>
                    <?php \yii\widgets\ListView::end();?>
                    </tbody>
                </table>
            <?php } ?>
            <?php Pjax::end(); ?>

            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => new \yii\data\ActiveDataProvider([
                    'query' => \api\models\V1\OrderTotal::find()->where(['order_id'=>$model->order_id])->orderBy(['sort_order' => SORT_ASC]),
                    'pagination'=>['pageSize' => 20]
                ]),
                'columns' => [
                    'code',
                    'title',
                    'text',
                    'value',
                    'remark',
                ],
            ]) ?>
            <?php Pjax::end(); ?>
        </div>
        <div class="tab-pane fade" id="tab_6_5">
            <div class="history-view">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ActiveDataProvider([
                        'query' => \api\models\V1\OrderHistory::find()->where(['order_id'=>$model->order_id])->orderBy(['order_history_id' => SORT_DESC]),
                        'pagination'=>['pageSize' => 20]
                    ]),
                    'columns' => [
                        'orderStatus.name',
                        'comment',
                        'date_added',
                    ],
                ]) ?>
                <?php Pjax::end(); ?>
            </div>
        </div>

        <div class="tab-pane fade" id="tab_6_6">
            <div class="totals-view">
                    <h3>价格调整</h3>
                    <?php $form = ActiveForm::begin(); ?>
                        调整说明：<?php echo Html::Input('text','title',null, ['class'=>'form-control','id'=>'change_title']);?>
                        <br>调整金额：<?php echo Html::Input('text','total',null, ['class'=>'form-control','id'=>'change_value']);?>
                        <br><?= Html::submitButton('添加记录', ['class' => 'btn btn-primary']) ?>
                    <?php ActiveForm::end(); ?>

            </div>
            <?php if($model->order_status_id == 12 || $model->order_status_id == 13 || $model->order_status_id == 7 ){?>
                <div class="totals-view">
                    <h3>订单状态调整</h3>
                    <?php $form = ActiveForm::begin(['action'=>'/order/change-status']); ?>
                    <?php echo Html::hiddenInput('id',$model->order_id)?>
                    <?php echo Html::dropDownList('order_status',$model->order_status_id,[12=>'待审核',13=>'审核通过',7=>'取消订单',2=>'支付成功'],['class'=>'form-control'])?>

                    <br><?= Html::submitButton('修改订单状态', ['class' => 'btn btn-success']) ?>
                    <?php ActiveForm::end(); ?>

                </div>
    <?php }?>

        </div>
    </div>
    </div>

</div>


<?php $this->beginBlock("JS_Block")?>
    
    $(".btn-primary").on("click",function(){
        if(isNaN($("#change_value").val()) == true || $("#change_value").val() == ''){
            alert('调整金额必须为数字');
            return false;
        }
        if($("#change_title").val() == ''){
            alert('调整说明不能为空');
            return false;
        }
    });
    $(".btn-success").on("click",function(){

        if(!confirm("确定要修改订单状态？"))
        {
            return false;
        }
    });

<?php $this->endBlock()?>

<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);

