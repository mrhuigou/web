<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/9
 * Time: 16:19
 */
$this->title = "地推点收货";
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="/">
            <i class="aui-icon aui-icon-home green f28"></i>
        </a>
        <div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2 scan_btn" href="javascript:;" >
            <i class="aui-icon aui-icon-scan green f28"></i>
        </a>
    </div>
</header>
<div class="mt50">
    <div class="bc tc p10">
        <p class="tc w f16 fb ">扫一扫收货码</p>
        <a class="scan_btn tc w" href="javascript:;" >
            <i class="aui-icon aui-icon-scan green " style="font-size: 10rem"></i>
        </a>
        <p class="tit--">输入收货码</p>
        <div class="m10">
            <input class="input appbtn tl w mb10" id="inputConfirm" placeholder="输入收货码" type="tel">
            <a class="btn lbtn w greenbtn" id="btnConfirm">确认收货</a>
        </div>

    </div>
</div>
<?php if ($plan) { ?>

    <div id="cart-list">
        <!--            <p class="mt5 bg-wh pt10 pb10 pl10">自提点地址：<i class="red">--><?php //echo $model->address?><!--</i></p>-->
        <p class=" mb5 bg-wh pt5 pb5 pl10">最晚提货时间：<i class="red"><?php echo '当日 '.$plan->shipping_end_time?></i></p>

        <?php foreach ($products as $key=>$value) { ?>
            <div class="flex-col flex-center store-item bdb  whitebg pr" data-content="<?=$value->product_code?>">
                <?php if(!$value->stock || ($value->stock && $value->stock->quantity <=0)){?>
                    <i class="item-tip iconfont">&#xe696;</i>
                <?php }?>
                <div class="flex-item-2 flex-row flex-middle flex-center p5 item-img">
                    <a href="javascript:void (0)">
                        <img src="<?=\common\component\image\Image::resize($value->product->image,100,100)?>"
                             class=" w">
                    </a>
                </div>
                <?php if(empty($cart)){
                    $quantity = 1;
                }else{
                    if(isset($cart[$value->product_code]) && $cart[$value->product_code] >0){ //购物车内有该商品
                        $quantity = $cart[$value->product_code];
                    }else{
                        $quantity = 1;
                    }
                }?>
                <div class="flex-item-9 flex-row   p5">
                    <div class="w">
                        <h2 class="row-one"><?=$value->product->description->name?></h2>
                        <p class="gray9  mt2"><?=$value->product->getSku()?></p>
                    </div>
                    <div class="flex-col w">
                        <div class="red  fb lh200 f14 flex-item-6">
                            <p>￥<i class="product_total"><?=round(bcmul($value->price,$quantity,4),2)?></i></p>
                        </div>
                        <div class="flex-item-6 flex-row  flex-center">
                            <p class="clearfix">
                                <?php if(!$value->stock || ($value->stock && $value->stock->quantity <=0)){?>
                            <p class="redbg white stock_status tc p5" style="display: block">库存不足</p>
                            <?php }else{?>
                                <span>库存：</span>
                                <input type="text"  class="red" readonly value="<?php echo $value->stock->quantity ?>"  >
                            <?php }?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php }else{ ?>
    <div id="cart-list">暂无活动商品</div>
<?php } ?>



    <style>
        .weui-popup-container{
            z-index: 9999;
        }
    </style>
    <!--基础形式及全屏-->
    <div id="full" class="weui-popup-container" >

    </div>


<?php
$this->registerJsFile("https://res.wx.qq.com/open/js/jweixin-1.0.0.js", ['depends' => [\h5\assets\AppAsset::className()]]);?>
<script>
<?php
$this->beginBlock('JS_INIT')?>
var ground_push_code = '<?php echo $ground_push_point->code?>';
$("#btnConfirm").on('click',function () {

    var input_string = $("#inputConfirm").val();
    if(!input_string){
        $.alert("收获码必填！");
        return false;
    }
    $.showLoading("正在加载");
    $.post('/ground-push/get-order-by-string',{code_string:input_string,type:'input',ground_push_code:ground_push_code},function(data){
        $.hideLoading();
        if(data.status){
            //$("#confirm_form_order").html(data.html);
            $("#full").html(data.html);
            $("#full").popup();
        }else{
            $.alert(data.message);
        }

    },'json');
});

$('.scan_btn').on('click',function(){
    $.showLoading("正在加载");
    $.getJSON('/share/sign?url=' + encodeURIComponent(location.href), function (res) {
        $.hideLoading();
        wx.config(res);
        wx.ready(function(){
            wx.scanQRCode({
                needResult: 1,
                desc: '地推点扫描收货',
                success: function (res) {
                    //$.alert(res.resultStr);
                    $.post('/ground-push/get-order-by-string',{code_string:res.resultStr,type:'scan',ground_push_code:ground_push_code},function(data){
                        $.hideLoading();
                        if(data.status){
                            //$("#confirm_form_order").html(data.html);
                            $("#full").html(data.html);
                            $("#full").popup();
                        }else{
                            $.alert(data.message);
                        }

                    },'json');
                    //$.alert(JSON.stringify(res));
                },
                cancel: function (res) {
                    $.toast('取消扫描!', 'cancel');
                },
                fail: function (res) {
                    $.alert(JSON.stringify(res));
                }
            });
        });
    });
});
$("body").on('click','#confirm_self_take',function(){
    var scan_string = $(this).attr('data-content');
    var type = $(this).attr('data-key');
    $.showLoading("正在加载");
    $.post('/ground-push/confirm-self-take',{code_string:scan_string,type:type,ground_push_code:ground_push_code},function(data){
        $.hideLoading();
        //alert(JSON.stringify(data));
        $.alert(data.message);
        $("#inputConfirm").val("");
        $.closePopup();

    },'json');

});
<?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'], \yii\web\View::POS_END);
?>