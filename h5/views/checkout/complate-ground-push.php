<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='支付结果';
$this->context->layout = 'main_other';
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="/">
            <i class="iconfont">&#xe63f;</i>
        </a>
        <div class="flex-item-8 f16">
            <?= \yii\helpers\Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/user/index']) ?>">
            <i class="aui-icon aui-icon-people green f28"></i>
        </a>
    </div>
</header>
<section class="pt50">
    <div class="bg-wh">
        <img src="/assets/images/order-ok.png" class="w">
        <div class="tc pb5 " style="margin-top: -12px;">
            <?php if($model->status && $order_model->order_status_id==2){ //此处生成取货码?>
                <h2 class="f12 red">将该二维码出示给地推人员，扫描提货</h2>
                <img class="tc" id="qrcode" src="<?php echo \yii\helpers\Url::to(['/ground-push/get-qrcode', 'order_id' => $order_model->order_id], true)?>">
                <h1 class="whitebg fb lh37 bdt bdb pl5 tc">地推点自提码(<i><?php if($en_code){ echo $en_code;}?></i>)</h1>
            <?php }elseif($order_model->order_status_id==1){ ?>
                <h2 class="f24 red">支付失败</h2>
                <p class="f18 gray9 mt10 mb10">付款金额：￥<?=floatval($model->total)?></p>
                <p class="gray9 mb10">支付遇到问题请联系客服 </p>
                <a class="btn btn-m btn-bd-green btn-pill" href="tel:4008556977">联系客服</a>

            <?php }else{ ?>
                <h2 class="f24 red">订单成功</h2>
                <p class="f18 gray9 mt10 mb10">订单状态：<?php echo $order_model->orderStatus->name?></p>
                <a class="btn btn-m btn-bd-green btn-pill" href="<?php \yii\helpers\Url::to(['/order/info','order_no'=>$order_model->order_no])?>">查看订单</a>
            <?php } ?>
        </div>
    </div>

    <script>
        <?php if($model->status && $order_model->order_status_id==2){?>
    <?php $this->beginBlock('J_Reviews') ?>

        if(!$("#qrcode").complete){
            //$.hideLoading();
            $.showLoading("加载提货码...");
            $('#qrcode').load(function(){
                $.hideLoading();
                getOrderStatus();
            });
            setTimeout(function () {
                $.hideLoading();
            },1300);
        }else{
            getOrderStatus();
        }

    function getOrderStatus() {
        $.get('<?=\yii\helpers\Url::to(['/ground-push/ajax-order-status','order_no'=>$order_model->order_no],true) ?>',function(data){
            if(data.status){
                    $.get('<?=\yii\helpers\Url::to(['/ground-push/ajax-is-new-customer'],true) ?>',function(res){
                        if(res.status){
                            layer.open({
                                content: '为您准备了新用户专享的福利！'
                                ,btn: ['领取免费商品', '去首页']
                                ,yes: function(index){
                                    location.href = '<?php echo \yii\helpers\Url::to(["coupon/coupon-rules",'id'=>4],true)?>';
                                    layer.close(index);
                                }
                                ,no:function (index) {
                                    location.href = '<?php echo \yii\helpers\Url::to(["site/index"],true)?>';
                                    layer.close(index);
                                }
                            });
                        }else{
                            layer.open({
                                content: '取货成功！'
                                ,btn: ['去首页']
                                ,yes: function(index){
                                    location.href = '<?php echo \yii\helpers\Url::to(["site/index"],true)?>';
                                    layer.close(index);
                                }
                            });
                        }

                    },'json');

            } else{
                 getOrderStatus();
            }
        },'json');
    }


    <?php $this->endBlock() ?>
        <?php }?>
    </script>
    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['J_Reviews'],\yii\web\View::POS_END);
    ?>
</section>
<?=\h5\widgets\Tools\Share::widget([
    'data'=>[
        'title' => '红包来袭，手慢无！！！',
        'desc' => "家润网，物美价廉，当日订单，当日送达。",
        'link' => \yii\helpers\Url::to(['/share/subscription'],true),
        'image' => 'https://m.365jiarun.com/images/gift-icon.jpg'
    ]
])?>

