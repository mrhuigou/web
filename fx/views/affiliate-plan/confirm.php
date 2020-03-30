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

		<?php if ($carts) { ?>

            <div class="store_contain whitebg " id="store_contain_<?= $point->id ?>">
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
            <div class="store_contain whitebg " id="store_contain_<?= $point->id ?>">
                <div class="mt5">
                    <div class="flex-row mt10 ">
                        <div id="address_list" class="">
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
                        <?php if($affiliate_info->mode == 'DOWN_LINE'){//分销商为线下时 显示自提点?>
                            <div class="flex-col flex-center store-item bdb  whitebg p5 item-address <?=$shipping_method == 2?"red":""?>">
                                <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center" style="margin-top: 10px;">
                                    <input type="radio" value="2" name="shipping_method"  class="item" <?=$shipping_method == 2?'checked':""?>>
                                    <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                </label>
                                <div class="flex-item-3 pt10">团长处自提</div>
                                <div class="flex-item-6 ">
                                    <p class="shipping-region"><?=$affiliate_info->zone_name.'-'.$affiliate_info->city_name.'-'.$affiliate_info->district_name?></p>
                                    <p class="shipping-address"><?=$affiliate_info->address?></p>
                                </div>
                            </div>
                        <?php }?>
                        </div>
                    </div>
                </div>
            </div>


            <?php foreach ($carts as $plan_id => $cart) {?>

                <?php $plan_info = \api\models\V1\AffiliatePlan::findOne(['affiliate_plan_id'=> $plan_id])?>
                <div class="graybg p10 store_totals">
                    <p class="mb5 clearfix lh150">
                            <span class="fr red fb"><?= $plan_info->name?></span>
                        <span class="fl fb">方案名称：</span>
                    </p>
                </div>
                <div class="graybg p10 store_totals">
                    <p class="mb5 clearfix lh150">
                            <span class="fr red fb"><?= date('Y-m-d',strtotime($plan_info->ship_end)) ?></span>
                        <span class="fl fb">配送时间：</span>
                    </p>
                </div>
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

choice_distribution_type();
//通过配送方式选择
function choice_distribution_type(){
    var distribution_type1 = <?=$model->distribution_type?>;
    $.showLoading("正在加载");
    $.post('/affiliate-plan/distribution-address',{distribution_type:distribution_type1},function(data){
        $.hideLoading();
        if(data.status){
            var address = data.data.address;
            var distribution_type = data.data.distribution_type;

            if(distribution_type == 1){
                if(Object.keys(address).length > 0){
                    $(".confirm-username").html(address.address_username);
                    $(".confirm-mobile").html(address.address_telephone);
                    $(".confirm-zone").html(address.zone + '-'+ address.city + '-'+ address.district);
                    $(".confirm-address").html(address.address_1);
                    $(".tab_1").show();$(".tab_2").show();$(".tab_3").hide();
                }else{
                    $(".tab_1").hide();$(".tab_2").hide();$(".tab_3").show();
                }
            }
            if(distribution_type == 2){
                $(".tab_1").show();$(".tab_2").hide();$(".tab_3").hide();

                if(Object.keys(address).length > 0){
                    $(".confirm-username").html(address.address_username);
                    $(".confirm-mobile").html(address.address_telephone);
                    $(".confirm-zone").html(address.zone + '-'+ address.city + '-'+ address.district);
                    $(".confirm-address").html(address.address_1);

                }else{

                }
            }
        }else{
            $.alert(data.message);
        }
    },'json');
}



$("#button_submit").click(function(){
    //$("#confirm_form_address").html($(".select_address").html());

    var telephone = $("#telephone").val();
    var firstname = $("#firstname").val();
    var confirm_zone= $(".shipping-region").eq($("input[type='radio']:checked").val() - 1).text();
    var confirm_address = $(".shipping-address").eq($("input[type='radio']:checked").val() - 1).text();

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
        $("#confirm_address").val($(".shipping-region").eq($("input[type='radio']:checked").val() - 1).text());
        $("#confirm_address_1").val($(".shipping-address").eq($("input[type='radio']:checked").val() - 1).text());
        $('#form-checkout').submit();
    }
});

$(".select_address").click(function () {

    var telephone = $(".confirm-mobile").text();
    var username = $(".confirm-username").text();
    var zone= $(".confirm-zone").text();
    var address_1 = $(".confirm-address").text();

    location.href='/affiliate-plan/edit-address?telephone='+ telephone + '&username=' + username + '&zone=' + zone + '&address_1='+address_1;

});

$("body").on("click","#close_pop",function(){
    layer.closeAll();
});
$("body").on("click",".save_address",function(){

    var telephone = $('#telephone').val();
    if(!telephone){
        alert("手机号码必须填写");
        $("#telephone").css('border-color',"red");
        return false;
    }
    var myreg =  /^1[3456789]\d{9}$/;
    if(!myreg.test(telephone))
    {
        alert('请输入有效的手机号码！');
        $("#telephone").css('border-color',"red");
        return false;
    }

    var firstname = $('#firstname').val();
    if(!firstname){
        alert("收货人姓名必须填写");
        $("#firstname").css('border-color',"red");
        return false;
    }

    var address_new = $('#address_new').val();
    if(!address_new){
        alert("详细地址必须填写");
        $("#address_new").css('border-color',"red");
        return false;
    }
    var start = $('#start').val();
    if(!start){
        alert("地区必须填写");
        $("#start").css('border-color',"red");
        return false;
    }

    $(".confirm-mobile").html(telephone);
    $(".confirm-username").html(firstname);
    $(".confirm-zone").html(start);
    $(".confirm-address").html(address_new);
    layer.closeAll();
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
