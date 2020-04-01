<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
$this->title = '订单确认';
?>
<header class="header">
    <div class="flex-col tc">
        <a class="flex-item-2" href="javascript:history.back()">
            <i class="aui-icon aui-icon-left green f28"></i>
        </a>
        <div class="flex-item-8 f16">
			<?= Html::encode($this->title) ?>
        </div>
<!--        <a class="flex-item-2" href="--><?//= \yii\helpers\Url::to(['/cart/index']) ?><!--">-->
<!--            <i class="aui-icon aui-icon-cart green f28"></i>-->
<!--        </a>-->
    </div>
</header>
<div class="pb50">
    <section class="veiwport pl5 pr5 pt5">
		<?php if (Yii::$app->getSession()->getFlash('error')) { ?>
            <div class=" p10  tc bd w redbg mb5">
                <p class="white"><?php echo Yii::$app->getSession()->getFlash('error') ?></p>
            </div>
		<?php } ?>
		<?php $form = ActiveForm::begin(['id' => 'form-checkout', 'fieldConfig' => [
			'template' => "<div class=\"mt5  clearfix\">{input}</div>{error}",
			'inputOptions' => ["class" => 'appbtn tl w'],
			'errorOptions' => ['class' => 'red fb tc db']
		],]); ?>
<!--        <input type="text" id="confirm_firstname"   name="confirm_firstname" style="display: none" value="">-->
<!--        <input type="text" id="confirm_telephone"   name="confirm_telephone" style="display: none" value="">-->
        <input type="text" id="confirm_address"   name="confirm_address" style="display: none" value="">
        <input type="text" id="confirm_address_1"   name="confirm_address_1" style="display: none" value="">
        <input type="text" id="confirm_lng"   name="confirm_lng" style="display: none" value="">
        <input type="text" id="confirm_lat"   name="confirm_lat" style="display: none" value="">

		<?php if ($carts) { ?>

            <div class="store_contain whitebg " id="store_contain">
                <div class="mt5">
                    <div class="flex-row ">
                        <div class="flex-item-3 p5 pt10 pl10"><i class="red">*</i>收货人</div>
                        <div class="flex-item-9 mt5 "><input type="text" class="input-text w" id="firstname" name="firstname" value="<?php echo Yii::$app->user->identity ? Yii::$app->user->identity->firstname : "";?>"></div>
                    </div>
                    <div class="flex-row mt10 ">
                        <div class="flex-item-3 pt10 pl10"><i class="red">*</i>收货电话</div>
                        <div class="flex-item-9 mb10 "><input type="text" class="input-text w " id="telephone" name="telephone" value="<?php echo Yii::$app->user->identity ? Yii::$app->user->identity->telephone: "";?>">     </div>
                    </div>
                </div>
            </div>
            <div class="store_contain whitebg " id="store_contain">
                <div class="mt5">
                    <div class="flex-row mt10 ">
                        <div id="address_list" class="">
                        <?php if($affiliate_info->is_deliver_home == 'true'){//分销商 是否配送到家?>
                            <div class="flex-col flex-center store-item bdb  whitebg p5 item-address <?=$shipping_method==1?"red":""?>">
                                <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center" style="margin-top: 10px;">
                                    <input type="radio" value="1" name="shipping_method"  class="item" <?=$shipping_method==1?'checked':""?>>
                                    <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                </label>

                                <div class="flex-item-3 pt10">配送到家</div>
                                <?php if(!empty($shipping_address)){?>
                                <div class="flex-item-6 ">
                                    <p class="shipping-region"><?=$shipping_address['zone_name'].'-'.$shipping_address['city_name'].'-'.$shipping_address['district_name']?></p>
                                    <p class="shipping-address"><?=$shipping_address['address']?></p>
                                    <p class="shipping-lng" style="display: none"><?=$shipping_address['lng']?></p>
                                    <p class="shipping-lat" style="display: none"><?=$shipping_address['lat']?></p>
                                </div>
                                <div class="flex-item-2 flex-row flex-middle flex-center">
                                    <?php $in_range = Yii::$app->request->get('in_range',1);?>
                                    <a href="<?=\yii\helpers\Url::to(['/affiliate-plan/edit-address','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>" class="iconfont gray9 ">&#xe615;</a>
                                </div>
                                <?php }else{ ?>
                                <div class="flex-item-6 ">
                                    <a class="db  rarrow whitebg f14 tc" href="<?=\yii\helpers\Url::to(['/affiliate-plan/edit-address','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>"><span class="iconfont fb">&#xe60c;</span>创建您的收货地址 </a>
                                </div>
                                <?php }?>

                            </div>
                            <?php }?>
                        <?php if($affiliate_info->mode == 'DOWN_LINE' || $affiliate_info->is_deliver_home == 'false'){//分销商为线下时 显示自提点 或不允许配送到家?>
                            <div class="flex-col flex-center store-item bdb  whitebg p5 item-address <?=$shipping_method == 2 || $affiliate_info->is_deliver_home == 'false'?"red":""?>">
                                <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center" style="margin-top: 10px;">
                                    <input type="radio" value="2" name="shipping_method"  class="item" <?=$shipping_method == 2 || $affiliate_info->is_deliver_home == 'false'?'checked':""?>>
                                    <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                </label>
                                <div class="flex-item-3 pt10">团长处自提</div>
                                <div class="flex-item-6 ">
                                    <p class="shipping-region"><?=$affiliate_info->zone_name.'-'.$affiliate_info->city_name.'-'.$affiliate_info->district_name?></p>
                                    <p class="shipping-address"><?=$affiliate_info->address?></p>
                                    <p class="shipping-lng" style="display: none"><?=$affiliate_info->lng?></p>
                                    <p class="shipping-lat" style="display: none"><?=$affiliate_info->lat?></p>
                                </div>
                            </div>
                        <?php }?>
                        </div>
                    </div>
                </div>
            </div>


            <?php foreach ($carts as $plan_id => $cart) {?>

                <?php $plan_info = \api\models\V1\AffiliatePlan::findOne(['affiliate_plan_id'=> $plan_id])?>
                <p class=" mb5 bg-wh pt5 pb5 pl10 graybg p10 store_totals">
                    配送时间：<i class="red"><?= date('Y-m-d',strtotime($plan_info->ship_end)) ?></i>
                    &nbsp;&nbsp;&nbsp;
                    方案名称：<i class="red"><?= $plan_info->name?></i>
                </p>
                <div class="store_contain whitebg " id="store_contain_<?= $plan_info->affiliate_plan_id ?>">
                    <?php $cart_total = 0;?>
                    <?php foreach ($cart as $key => $value) { ?>
                        <?php
                        //单笔订单金额计算$cart_total
                        $cart_total = $cart_total + $value['product_total'];

                        ?>
                        <?php
                            $product = \api\models\V1\Product::findOne(['product_code'=>$value['pv']->product_code ]);
                            //对展示图进行处理  没有图片时 使用包装图片
                            //对商品图进行处理
                            $imagelist = '';
                            $images = $value['pv']->product->productBase->imagelist;
                            if($images){
                                foreach ($images as $value1){
                                    if(empty($imagelist)){
                                        $imagelist = $value1;
                                    }
                                }
                            }
                        ?>
                        <div class="flex-col tc p5 graybg" style="border-bottom: 1px dotted #999;">
                            <div class="flex-item-3">
                                <a href="<?= \yii\helpers\Url::to(['product/index', 'product_code' => $product->product_code, 'shop_code' => $product->store_code]) ?>">
                                    <img src="<?= \common\component\image\Image::resize($value['pv']->image_url?:$imagelist, 100, 100) ?>"
                                         class="db w">
                                </a>
                            </div>
                            <div class="flex-item-7 tl pl10">
                                <h2><?= $product->description->name ?></h2>
                                <p class="gray9  mt2"><?= $product->getSku() ?></p>

                            </div>
                            <div class="flex-item-2 tc flex-middle flex-row">
                                <p class="blue mb5"> x<?= $value['qty'] ?></p>
                                <p class="red  fb">￥<?= $value['pv']->price_type == 1 ? $value['pv']->price:$value['pv']->product->productBase->price; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="graybg p10 store_totals">
                    <p class="mb5 clearfix lh150">
                            <span class="fr red fb">￥<em
                                        class="total"><?= $cart_total?></em></span>
                        <span class="fl fb">订单金额：</span>
                    </p>
                </div>

                <div style="border:1px dashed #000;margin-top: 30px;"></div>
            <?php } ?>

            <div class="graybg p10 store_totals">
                <?php if ($totals) { ?>
                    <?php foreach ($totals as $value) { ?>
                        <?php if($value['code'] == 'total'){?>
                            <p class="mb5 clearfix lh150">
                                        <span class="fr red fb">￥<em
                                                    class="<?= $value['code'] ?>"><?= $value['value'] ?></em></span>
                                <span class="fl fb"><?= $value['title'] ?>：</span>
                            </p>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>


		<?php } ?>


		<?php ActiveForm::end(); ?>
    </section>
</div>
<div class="fx-bottom flex-col bdt whitebg ">
    <div class="flex-item-8 flex-row flex-middle flex-center  p15  ">
        <p> 应付：<span class="red f16 fb">￥<em id="pay_total"><?= $pay_total ?></em></span></p>
    </div>
    <a id="button_submit" href="javascript:;"
       class="flex-item-4 flex-row flex-middle db flex-center tc fb  white p15  greenbg" style="line-height: 22px;">
        提交结算
    </a>
</div>
<div id="confirm_form_order" class="bg-f0" style="display: none;">
    <h2 class="w p10 tc bg-wh">请确认收货地址</h2>
<!--    <div class=" m5 p5  bg-wh">-->
<!--        <div class="colorbar"></div>-->
<!--        <div id="confirm_form_address"></div>-->
<!--        <div class="colorbar "></div>-->
<!--    </div>-->
    <div class="   m5 ">
        <div id="confirm_form_firstname" class="flex-col w flex-middle bg-wh  p10 ">
        </div>
        <div id="confirm_form_telephone" class="flex-col w flex-middle bg-wh p10 ">
        </div>
        <div id="confirm_form_region" class="flex-col w flex-middle bg-wh  p10 ">
        </div>
        <div id="confirm_form_address" class="flex-col w flex-middle bg-wh p10 ">
        </div>
    </div>
    <div class="flex-col">
        <a class="flex-item-6 tc red fb p15 bg-wh" href="javascript:;" id="confirm_cannel">去修改</a>
        <a class="flex-item-6 tc greenbg white fb p15 " href="javascript:;" id="confirm_pay">去支付</a>
    </div>
</div>
<script>
<?php $this->beginBlock('JS_END') ?>


$("#button_submit").click(function(){
    //$("#confirm_form_address").html($(".select_address").html());

    var is_deliver_home = <?= $affiliate_info->is_deliver_home?>;
    var telephone = $("#telephone").val();
    var firstname = $("#firstname").val();
    var confirm_zone= $(".shipping-region").eq($("input[type='radio']:checked").val() - 1).text();
    var confirm_address = $(".shipping-address").eq($("input[type='radio']:checked").val() - 1).text();
    if(!is_deliver_home){
        var confirm_zone= $(".shipping-region").eq(0).text();
        var confirm_address = $(".shipping-address").eq(0).text();
    }

    if(!firstname){
        $.alert("收货人姓名必须填写");
        $("#firstname").css('border-color',"red");
        return false;
    }
    if(!telephone){
        $.alert("手机号码必须填写");
        $("#telephone").css('border-color',"red");
        return false;
    }

    var myreg =  /^1[3456789]\d{9}$/;
    if(!myreg.test(telephone))
    {
        $.alert('请输入有效的手机号码！');
        $("#telephone").css('border-color',"red");
        return false;
    }
    if(!confirm_address){
        $.alert("详细地址必须填写");
        $("#telephone").css('border-color',"red");
        return false;
    }
    if(!confirm_zone){
        $.alert("地区必须填写");
        $("#telephone").css('border-color',"red");
        return false;
    }

    var firstname_confirm="";
    firstname_confirm+='<div class="flex-item-4 mb5">姓名：</div><div class="flex-item-8  tr mb5">'+ $("#firstname").val()+' </div>';
    $("#confirm_form_firstname").html(firstname_confirm);

    var telephone_confirm = "";
    telephone_confirm = '<div class="flex-item-4 mb5">手机：</div><div class="flex-item-8  tr mb5">'+ $("#telephone").val()+' </div>';
    $("#confirm_form_telephone").html(telephone_confirm);

    var region_confirm="";
    region_confirm+='<div class="flex-item-4 mb5">收货地址：</div><div class="flex-item-8  tr mb5">'+ confirm_zone+' </div>';
    $("#confirm_form_region").html(region_confirm);

    var address_confirm = "";
    address_confirm = '<div class="flex-item-4 mb5">详细地址：</div><div class="flex-item-8  tr mb5">'+ confirm_address+' </div>';
   $("#confirm_form_address").html(address_confirm);

    maskdiv($("#confirm_form_order"),"bottom");
});
$("#confirm_cannel").click(function(e){
    e.stopPropagation();
    $("#confirm_form_order").slideUp();
    $(".maskdiv").remove();
    $(".content").scrollTop(0);
});
var post_flag=true;
$("#confirm_pay").click(function(){
    if(post_flag){
        post_flag=false;
        $.showLoading("正在提交");
        // $('#form-checkout').submit();
        var is_deliver_home = <?= $affiliate_info->is_deliver_home?>;
        $("#confirm_address").val($(".shipping-region").eq($("input[type='radio']:checked").val() - 1).text());
        $("#confirm_address_1").val($(".shipping-address").eq($("input[type='radio']:checked").val() - 1).text());
        $("#confirm_lng").val($(".shipping-lng").eq($("input[type='radio']:checked").val() - 1).text());
        $("#confirm_lat").val($(".shipping-lat").eq($("input[type='radio']:checked").val() - 1).text());
        if(!is_deliver_home){
            $("#confirm_address").val($(".shipping-region").eq(0).text());
            $("#confirm_address_1").val($(".shipping-address").eq(0).text());
            $("#confirm_lng").val($(".shipping-lng").eq(0).text());
            $("#confirm_lat").val($(".shipping-lat").eq(0).text());
        }
        $('#form-checkout').submit();
    }
});

$("#address_list  .item-address").click(function(){
    $(this).addClass('red').siblings().removeClass('red');
    $(this).find('input:radio').attr('checked',true);
});

<?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>
