<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title = '订单确认';
?>
<div class="w1100 bc pt10" id="checkout">
        <!--面包屑导航-->
        <p class="mb10"></p>
        <p class="shopcart_step step2"></p>
        <?php if(Yii::$app->getSession()->getFlash("errror")){?>
        <p class="red"><?=Yii::$app->getSession()->getFlash("errror")?></p>
        <?php } ?>
        <?php $form = ActiveForm::begin(['id' => 'form-checkout','fieldConfig' => [
            'template' => '<h2 class="fb f14">{label}</h2>{input}{error}',
            'inputOptions' => ["class"=>'input minput f14 w200'],
            'errorOptions'=>['class'=>'red mt5 mb5 db']
        ],  ]);?>
            <!--收货信息 start-->
            <div class="whitebg bd p15 mb10">
                <?= $form->field($model, 'address_id')->widget(\frontend\widgets\Checkout\Address::className());?>
                <div class="line_dash mt10 mb10"></div>
                <?= $form->field($model, 'invoice_type',['labelOptions'=>['class'=>'fb f14 ']])->inline()->radioList(['不需要发票','个人','企业'],[
                    'itemOptions'=>['labelOptions'=>['class' => 'radio-inline pr10']],
                    'onchange'=>'if($(this).find(":radio:checked").val()>0){ $("#invoice_value").show();}else{$("#invoice_value").hide();}'
                ]) ?>
                <p class="org f12 lh150">客户开具发票的必须提供税号（公司）、身份证号（个人）。</p>
                <div id="invoice_value" style="display: <?=$model->invoice_type?'block':'none'?>">
		            <?= $form->field($model, 'invoice_title', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 50, 'placeholder' => '个人姓名/单位名称']); ?>
		            <?= $form->field($model, 'invoice_value', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 18, 'placeholder' => '身份证号/公司税号']); ?>
                </div>
            </div>
            <?php foreach ($cart as $val) { ?>
                <div class="bd mt10 store_contain" id="store_contain_<?=$val['base']->store_id?>">
                    <div class="clearfix whitebg">
                        <div class="w360 p10 fl store_delivery">
                            <?=\frontend\widgets\Checkout\Delivery::widget(['store_id'=>$val['base']->store_id,'total'=>$val['total']])?>
                        </div>
                        <div class="w715 fr bdl store_product" style="min-height: 250px;">
                            <h2 class="p10 clearfix ">
                            <span class=" ml5 f14 fb "><?= $val['base']->name?></span>
                                <span class="blue fr"> <?php if ($val['base']->befreepostage == 1) { //是否包邮（满X元包邮）
                                        if ($val['base']->minbookcash > 0) {
                                            echo '店铺满' . $val['base']->minbookcash . '元包邮';
                                        } else {
                                            echo '店铺包邮';
                                        }
                                    }
                                    ?>
                                </span>
                            </h2>
                            <div class="tb-mxh">
                                <table cellpadding="0" cellspacing="0" class="shopcart_list w">
                                    <thead>
                                    <tr>
                                        <th>商品名称</th>
                                        <th>商品规格</th>
                                        <th>单价</th>
                                        <th>数量</th>
                                        <th>小计</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($val['products'] as $key => $value) { ?>
                                        <tr>
                                            <td width="40%" class="clearfix" style="padding-left:10px; ">
                                                <a href="<?= \yii\helpers\Url::to(['product/index', 'product_base_code' => $value->product->product_base_code, 'shop_code' => $value->product->store_code]) ?>" class="clearfix">
                                                    <img class="fl"
                                                        src="<?= \common\component\image\Image::resize($value->product->image, 100, 100) ?>"
                                                        width="55" height="55" class="bd mr10"
                                                        alt="<?php echo $value->product->description->name; ?>"
                                                        title="<?php echo $value->product->description->name; ?>"/>
                                                <p class="fl mt5  tl pinfo w230">
                                                    <?php echo $value->product->description->name; ?>
                                                    <br>
                                                    <span class="org"><?= $value->product->getSku()?></span>
                                                </p>
                                                </a>
                                                <?=frontend\widgets\Checkout\Promotion::widget(['promotion'=>$value->getPromotion(),'qty'=>$value->getQuantity()])?>
                                            </td>
                                            <td width="14%">
                                                <i> <?= $value->product->format ?></i>
                                            </td>
                                            <td width="12%">
                                                <div style="line-height:18px;">
                                                    <i class="vip_price"> ￥<?= $value->getPrice() ?></i> <br/>
                                                    <i class="del gray9">￥<?= $value->product->price ?></i> <br/>
                                                    <i class="greenbg white pl5 pr5 product-discount-price">立省:￥<?php echo number_format($value->product->price - $value->getPrice(), 2); ?></i>
                                                </div>
                                            </td>
                                            <td width="10%" class="cart_pt_add_low_td">
                                                <?= $value->quantity ?>
                                                <?php if (!$value->hasStock()) { ?>  <span
                                                    class="stock_status">库存不足</span>
                                                <?php } ?>
                                            </td>
                                            <td width="10%" rowspan="1" class="org f14 product-sub-total">
                                                ￥<?=$value->getCost()?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <div class="store-promotion"><?=frontend\widgets\Checkout\StorePromotion::widget(['promotion'=>$val['promotion'],'coupon_gift'=>$val['coupon_gift']])?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix order-extra">
                        <?=\frontend\widgets\Checkout\Coupon::widget(['store_id'=>$val['base']->store_id,'product'=>$val['products']])?>
                        <div class="fr tr f14 w150 pl25 lh150 store_totals" >
                            <?php foreach ($val['totals'] as $total) { ?>
                                <p ><?= $total['title'] ?> :<i class="org">￥<em class="<?=$total['code']?>"><?= number_format($total['value'], 2)?></em></i></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- end -->
        <p class="clearfix mt10 pb20">
            <a href="<?php echo yii\helpers\Url::to(["cart/index"]); ?>" class="btn btn_big greenBtn fl">返回购物车</a>
            <span class="fr">
                    <span class="f18 dib vm mr15">支付总额:<i class="red">￥<em id="pay_total"><?php echo $pay_total; ?></em></i></span>
					<button type="button" class="btn btn_big orgBtn vm" id="confirm_pay">提交订单</button>
			</span>
        </p>
        <?php ActiveForm::end(); ?>
    </div>
<?php $this->beginBlock('JS_END') ?>
function AsyUpdateShopTotals(store_id){
var delivery_type=$("#store_contain_"+store_id).find('.delivery_type').val();
var delivery_date=$("#store_contain_"+store_id).find('.delivery_date').val();
var delivery_time=$("#store_contain_"+store_id).find('.delivery_time').val();
var delivery_station_id=$("#store_contain_"+store_id).find('.delivery_station_id').val();
var delivery_station_username=$("#store_contain_"+store_id).find('.delivery_station_username').val();
var delivery_station_telephone=$("#store_contain_"+store_id).find('.delivery_station_telephone').val();
var customer_coupon_ids=$("#store_contain_"+store_id).find('.store_coupon_data').val();
if(delivery_type=='delivery'){
delivery_station_id=0;
}
var datas={'store_id':store_id,'delivery_type':delivery_type,'delivery_station_id':delivery_station_id,'customer_coupon_id':customer_coupon_ids};
$.post('<?=\yii\helpers\Url::to('/checkout/order-ajax',true)?>',datas,function(data){
if(data && data.status){
$("#store_contain_"+datas.store_id).find(".store_totals").html(data.data);
$("#store_contain_"+store_id).find(".store-promotion").html(data.store_promotion);
$("#store_contain_"+datas.store_id).find(".delivery_station").load('<?=\yii\helpers\Url::to('/checkout/delivery-station-ajax',true)?>',datas);
var pay_total=0;
$(".total").each(function(){
pay_total=FloatAdd(pay_total,$(this).html());
});
$("#pay_total").html(pay_total);
}else{
alert(data.data);
}
},'json');
}
var post_flag=true;
$("#confirm_pay").click(function(){
if(post_flag){
post_flag=false;
$(this).html('正在提交...');
$('#form-checkout').submit();
}
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>