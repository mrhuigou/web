<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
?>
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_6_1" data-toggle="tab">订单详情</a>
        </li>
        <li class="">
            <a href="#tab_6_2" data-toggle="tab">支付信息</a>
        </li>
        <li>
            <a href="#tab_6_3" data-toggle="tab">寄送地址</a>
        </li>
        <li>
            <a href="#tab_6_4" data-toggle="tab">商品清单</a>
        </li>
        <li>
            <a href="#tab_6_5" data-toggle="tab">订单历史</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_6_1">
            <div class="order-view">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        // 'order_id',
                        // 'order_type_code',
                        'order_no',
                        'invoice_no',
                        'invoice_prefix',
                        // 'platform_id',
                        'platform_name',
                        'platform_url:url',
                        // 'store_id',
                        'store_name',
                        // 'store_url:url',
                        // 'customer_id',
                        // 'customer_group_id',
                        'firstname',
                        // 'lastname',
                        'email:email',
                        'telephone',
                        // 'gender',
                        'merge_code',
                        'payment_deal_no',
                        'payment_method',
                        // 'payment_code',
                        'total',
                        'comment:ntext',
                        // 'order_status_id',
                        'orderStatus.name',
                        // 'affiliate_id',
                        // 'commission',
                        // 'language_id',
                        // 'currency_id',
                        // 'currency_code',
                        // 'currency_value',
                        'ip',
                        'user_agent',
                        // 'accept_language',
                        'date_added',
                        'date_modified',
                        // 'invoice_temp',
                        // 'invoice_title',
                        // 'trade_account',
                        // 'use_date',
                        // 'time_range',
                        // 'use_nums',
                        // 'use_code',
                        // 'delivery_type',
                        // 'in_cod',
                        // 'sent_to_erp',
                        // 'sent_time',
                        // 'parent_id',
                        // 'cycle_store_id',
                        // 'cycle_id',
                        // 'periods',
                    ],
                ]) ?>

            </div>
        </div>
        <div class="tab-pane fade" id="tab_6_2">
            <div class="payment-view">
                <?php if(!is_null($payment)){ ?>
                    <?= DetailView::widget([
                        'model' => $payment,
                        'attributes' => [
                            // 'order_payment_id',
                            // 'order_id',
                            // 'customer_id',
                            // 'payment_firstname',
                            'payment_deal_no',
                            'payment_method',
                            // 'payment_code',
                            'total',
                            'remark',
                            'date_added',
                            // 'type_id',
                            // 'input_total',
                            // 'is_send',
                            // 'send_time',
                        ],
                    ]) ?>
                <?php }else{
                    echo '暂无支付信息';
                }?>
            </div>
        </div>
        <div class="tab-pane fade" id="tab_6_3">
            <div class="shipping-view">
                <?php if(!is_null($shipping)){ ?>
                    <?= DetailView::widget([
                        'model' => $shipping,
                        'attributes' => [
                            // 'order_shipping_id',
                            // 'order_id',
                            'shipping_firstname',
                            // 'shipping_lastname',
                            'shipping_telephone',
                            // 'shipping_gender',
                            'shipping_postcode',
                            'shipping_address_1',
                            'shipping_country',
                            // 'shipping_country_id',
                            // 'shipping_country_code',
                            'shipping_zone',
                            // 'shipping_zone_id',
                            // 'shipping_zone_code',
                            'shipping_city',
                            // 'shipping_city_id',
                            // 'shipping_city_code',
                            'shipping_district',
                            // 'shipping_district_id',
                            // 'shipping_district_code',
                            // 'shipping_address_format',
                            'shipping_method',
                            // 'shipping_code',
                            'delivery',
                            // 'delivery_code',
                            // 'post_type',
                            // 'shipping_is_send',
                        ],
                    ]) ?>
                <?php }else{
                    echo '暂无寄送信息';
                }?>
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
                            'store_id',
                            'store_code',
                            'product_id',
                            'product_code',
                            'model',
                            'name',
                            'quantity',
                            'old_price',
                            'price',
                            'total',
                            // 'tax',
                            // 'reward',
                            'unit',
                            'format',
                            // 'from_table',
                            // 'from_id',
                            'remark',
                        ],
                    ]); ?>
                    <p style="padding: 0 20px; line-height: 1.8" class="text-right">
                    <?php foreach ($total as $value) {
                        echo '<b>'.$value['title'].'：</b>'.$value['text'].'<br>';
                    }?>
                    </p>
                <?php }else{
                    echo '暂无商品信息';
                }?>
            </div>
        </div>
        <div class="tab-pane fade" id="tab_6_5">
            <div class="history-view">
                <?php if(!is_null($history)){ ?>
                    <?= GridView::widget([
                        'dataProvider' => $history,
                        'columns' => [
                            // 'order_history_id',
                            // 'order_id',
                            'orderStatus.name',
                            // 'notify',
                            'comment',
                            'date_added',
                        ],
                    ]); ?>
                <?php }else{
                    echo '暂无商品信息';
                }?>
            </div>
        </div>
    </div>
    </div>
