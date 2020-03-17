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
        <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/cart/index']) ?>">
            <i class="aui-icon aui-icon-cart green f28"></i>
        </a>
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


		<?php if ($carts) { ?>
            <div class="store_contain whitebg " id="store_contain_<?= $plan->affiliate_plan_id ?>">
                <div class="mt5">
                    <div class="flex-row ">
                        <div class="flex-item-2 p5 pt10 pl10"><i class="red">*</i>姓名</div>
                        <div class="flex-item-10 mt5 "><input type="text" class="input-text w" id="firstname" name="firstname" value="<?php echo $fx_user_info['firstname'] ? $fx_user_info['firstname'] : "";?>"></div>
                    </div>
                    <div class="flex-row mt10 ">
                        <div class="flex-item-2 pt10 pl10"><i class="red">*</i>手机</div>
                        <div class="flex-item-10 mb10 "><input type="text" class="input-text w " id="telephone" name="telephone" value="<?php echo $fx_user_info['telephone'] ? $fx_user_info['telephone']: "";?>">     </div>
                    </div>
                </div>
            </div>



            <div class="store_contain whitebg " id="store_contain_<?= $plan->affiliate_plan_id ?>">
                <div class="mt5">
                    <h2 class="clearfix p10">
                        <span class="fl ">地推点：<em class="org"><?= '默认收货地址' ?></em></span>

                    </h2>
                </div>
				<?php foreach ($carts as $key => $value) { ?>
                    <?php
                        $product = \api\models\V1\Product::findOne(['product_code'=>$value['pv']->product_code ]);
                    ?>
                    <div class="flex-col tc p5 graybg" style="border-bottom: 1px dotted #999;">
                        <div class="flex-item-3">
                            <a href="<?= \yii\helpers\Url::to(['product/index', 'product_code' => $product->product_code, 'shop_code' => $product->store_code]) ?>">
                                <img src="<?= \common\component\image\Image::resize($product->image, 100, 100) ?>"
                                     class="db w">
                            </a>
                        </div>
                        <div class="flex-item-7 tl pl10">
                            <h2><?= $product->description->name ?></h2>
                            <p class="gray9  mt2"><?= $product->getSku() ?></p>

                        </div>
                        <div class="flex-item-2 tc flex-middle flex-row">
                            <p class="blue mb5"> x<?= $value['qty'] ?></p>
                            <p class="red  fb">￥<?= $value['pv']->price; ?></p>
                        </div>
                    </div>
				<?php } ?>

                <div class="graybg p10 store_totals">
					<?php if ($totals) { ?>
						<?php foreach ($totals as $value) { ?>
                            <p class="mb5 clearfix lh150">
                                <span class="fr red fb">￥<em
                                            class="<?= $value['code'] ?>"><?= $value['value'] ?></em></span>
                                <span class="fl fb"><?= $value['title'] ?>：</span>
                            </p>
						<?php } ?>
					<?php } ?>
                </div>
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
    <h2 class="w p10 tc bg-wh">请确认姓名与手机号码</h2>
<!--    <div class=" m5 p5  bg-wh">-->
<!--        <div class="colorbar"></div>-->
<!--        <div id="confirm_form_address"></div>-->
<!--        <div class="colorbar "></div>-->
<!--    </div>-->
    <div class="   m5 ">
        <div id="confirm_form_shippingtime" class="flex-col w flex-middle bg-wh  p10 ">
        </div>
        <div id="confirm_form_telephone" class="flex-col w flex-middle bg-wh p10 ">
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
    var telephone = $("#telephone").val();
    var firstname = $("#firstname").val();

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


    var delivery_list="";
    delivery_list+='<div class="flex-item-4 mb5">姓名：</div><div class="flex-item-8  tr mb5">'+ $("#firstname").val()+' </div>';
    $("#confirm_form_shippingtime").html(delivery_list);

    var name_confirm = "";
    name_confirm = '<div class="flex-item-4 mb5">手机：</div><div class="flex-item-8  tr mb5">'+ $("#telephone").val()+' </div>';
   $("#confirm_form_telephone").html(name_confirm);

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
        $('#form-checkout').submit();
    }
});
<?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>
