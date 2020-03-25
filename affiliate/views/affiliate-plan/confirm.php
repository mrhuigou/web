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


		<?php if ($carts) { ?>
            <div class="store_contain whitebg " id="store_contain_<?= $plan->affiliate_plan_id ?>">
                <div class="mt5 ">
                    <div class="flex-row mt10 pt15">
                        <div class="flex-item-4  pl10"><i class="red">*</i>配送方式</div>
                        <div class="flex-item-8 ">
                            <?= $form->field($model, 'distribution_type', ['labelOptions' => ['class' => 'fb f14  ']])->inline()->radioList([ 1=>'配送到家', 2=>'团长处自提'], [
                                'itemOptions' => ['labelOptions' => ['class' => 'radio-inline ']],
                                'onchange' => '
                                var distribution_type1 = $(this).find(":radio:checked").val();
                                console.log($(this).find(":radio:checked").val());
                                choice_distribution_type();
                                //通过配送方式选择
function choice_distribution_type(){
    $.showLoading("正在加载");
    $.post(\'/affiliate-plan/distribution-address\',{distribution_type:distribution_type1},function(data){
        $.hideLoading();
        if(data.status){
           var address = data.data.address;
           var distribution_type = data.data.distribution_type;
   
            if(distribution_type == 1){
                if(Object.keys(address).length > 0){
                    $(".confirm-username").html(address.address_username);
                    $(".confirm-mobile").html(address.address_telephone);
                    $(".confirm-zone").html(address.city + \'-\'+ address.district);
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
                    $(".confirm-zone").html(address.city + \'-\'+ address.district);
                    $(".confirm-address").html(address.address_1);
                  
                }else{
                    
                }
            }
            
            
      
            
        }else{
            $.alert(data.message);
        }
    },\'json\');
};
                               
                                
                                
                                '
                            ])->label(false)?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="colorbar"></div>
                    <div class="p10 db  whitebg f14  flex-col"  id="addressTri">
                            <div class="flex-item-10  tab_1" style="display: none">
                                <p><em class="confirm-username" id="confirm-username"></em><em class="confirm-tel ml10 confirm-mobile" id="confirm-mobile"></em></p>
                                <p class="confirm-zone"></p>
                                <p class="confirm-address"> </p>
                            </div>
                            <div class="flex-item-2 tr pt20 green select_address tab_2" style="display: none">
                                修改<i class="iconfont f14 ">&#xe60b;</i>
                            </div>
                            <div class="select_address tab_3" style="display: none">
                                <a class="db p20  rarrow whitebg f14 tc" href="javascript:;"><span class="iconfont fb">&#xe60c;</span>创建您的收货地址 </a>
                            </div>
                    </div>

            <div class="colorbar "></div>


            <!--地址弹层-->
            <script id="addressPop" type="text/html">

                <div class="w bdb tc lh44 bs-b clearfix pr">
                    <a class="pa-tl" href="javascript:;" id="close_pop">
                        <i class="aui-icon aui-icon-left green f28"></i>
                    </a>
                    <span class="f16">编辑配送地址</span>
                    <!--        <a class="pa-tr pr5 cp gray6" href="--><?php //=\yii\helpers\Url::to(['/address/index','redirect'=>Yii::$app->request->getAbsoluteUrl(),'in_range'=>1])?><!--">编辑</a>-->
                </div>
                <div class="line-a flex-col w flex-middle red mb5">
                    <div class="flex-item-12">
                        因系统升级，<span class="fb">暂停黄岛区配送！</span>
                        给您带来的不便，我们深感抱歉！
                    </div>

                </div>
                <div class="p10 which addresslist pa-t graybg" style="top: 116px; bottom: 50px; overflow-y: scroll;">

                    <ul class="line-book mt5">
                            <li>
                                <div class="t">选择地区：</div>
                                <div class="c">
                                    <div class="weui-cell__bd">
                                        <?php $p = $model->province ? $model->province : '山东省';
                                        $c = $model->city ? $model->city : '青岛市';
                                        $d = $model->district ? $model->district : '市北区';
                                        ?>
                                        <input class="w f14" id="start" type="text"  value="<?php echo $p.' '.$c.' '.$d;?>">
                                    </div>
                                </div>
                            </li>
                    </ul>
                    <div class="c">
                        <div class="t">详细地址：</div>
                        <div class="weui-cell__bd">
                            <textarea name="a" placeholder='小区/写字楼/街道+楼号+楼层等' id='address_new' class='w f14' rows=2 style="height:45px;padding:5px;"><%:=address_1%></textarea>
                        </div>
                    </div>

                    <div class="store_contain whitebg ">
                        <div class="mt5">
                            <div class="flex-row ">
                                <div class="flex-item-2 p5 pt10 pl10"><i class="red">*</i>姓名</div>
                                <div class="flex-item-10 mt5 "><input type="text" class="input-text w" id="firstname" name="firstname" placeholder = '请填写收货人姓名' value="<%:=username%>"></div>
                            </div>
                        </div>
                    </div>

                    <div class="store_contain whitebg ">
                        <div class="mt5">
                            <div class="flex-row mt10 ">
                                <div class="flex-item-2 p5 pt10 pl10"><i class="red">*</i>手机</div>
                                <div class="flex-item-10 mb10 "><input type="text" class="input-text w " id="telephone" name="telephone" placeholder = '请填写收货人手机号' value="<%:=telephone%>"></div>
                            </div>
                        </div>
                    </div>
<!--                        --><?//= $form->field($model, 'address',['template' => '{label}<li>{input}</li>{error}'])->textarea(["placeholder" => '小区/写字楼/街道+楼号+楼层等','id'=>'address','class'=>'w f14 ','rows'=>2,'style'=>"height:45px;padding:5px;"])?>
<!--                        --><?//= $form->field($model, 'username', ['inputOptions' => ["placeholder" => '请填写收货人姓名']]) ?>
<!--                        --><?//= $form->field($model, 'telephone', ['inputOptions' => ["placeholder" => '请填写收货人电话号码']]) ?>









                </div>
                <div class="fx-bottom p5 tc graybg bdt">
                    <a class="btn mbtn w greenbtn save_address" href="#">保存</a>
                </div>
            </script>




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
                    $(".confirm-zone").html(address.city + '-'+ address.district);
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
                    $(".confirm-zone").html(address.city + '-'+ address.district);
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
    var telephone = $(".confirm-mobile").text();
    var confirm_username = $(".confirm-username").text();
    var confirm_zone= $(".confirm-zone").text();
    var confirm_address = $(".confirm-address").text();

    if(!confirm_username){
        $.alert("收货人姓名必须填写");
        $("#firstname").css('border-color',"red");
        return false;
    }
    if(!telephone){
        $.alert("手机号码必须填写");
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
    
    var myreg =  /^1[3456789]\d{9}$/;
    if(!myreg.test(telephone))
    {
        $.alert('请输入有效的手机号码！');
        $("#telephone").css('border-color',"red");
        return false;
    }


    var delivery_list="";
    delivery_list+='<div class="flex-item-4 mb5">姓名：</div><div class="flex-item-8  tr mb5">'+ confirm_username+' </div>';
    $("#confirm_form_shippingtime").html(delivery_list);

    var name_confirm = "";
    name_confirm = '<div class="flex-item-4 mb5">手机：</div><div class="flex-item-8  tr mb5">'+ telephone+' </div>';
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

/*地址选择*/
$("#start").cityPicker({
    title: "选择地址",
    onChange: function (picker, values, displayValues) {
        $("#province").val(displayValues[0]);
        $("#city").val(displayValues[1]);
        $("#district").val(displayValues[2]);

    }
});

$(".select_address").click(function () {
    var addressPop=$('#addressPop').html();
    var telephone = $(".confirm-mobile").text();
    var username = $(".confirm-username").text();
    var zone= $(".confirm-zone").text();
    var address_1 = $(".confirm-address").text();
    var html= template(addressPop, {telephone:telephone,username:username,address_1:address_1,zone:zone});
    layer.open({
        type: 1,
        area: 'auto',
        style: 'position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px;',
        content:html
    });
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

<?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
$this->registerJsFile("/assets/script/jqweui-picker.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/jqweui-city-picker.js",['depends'=>\h5\assets\AppAsset::className()]);
?>
