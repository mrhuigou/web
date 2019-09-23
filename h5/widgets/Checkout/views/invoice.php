<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/5/12
 * Time: 10:29
 */
?>
<?=$input?>

    <a href="javascript:;" class="open-invoice" data-target="#delivery_form_1" id="invoice_pop" >
        <div class="line-a flex-col w flex-middle delivery   p10 mb5 mt5">
            <div class="flex-item-2">
                发票：
            </div>
            <?php if($invoice){?>
                <div>
                    <div class="flex-item-8 invoice-default tr">
                        <?php if($invoice->type_invoice == 3){
                            echo "企业增值税专票";
                        }elseif($invoice->type_invoice == 2){
                            echo "企业增值税普票";
                        }else{
                            echo "个人发票";
                        }
                        ?>
                        <br>
                       <?php echo $invoice->title_invoice ?>
                    </div>
                    <div class="flex-item-2 tr green">
                        修改<i class="iconfont" style="color: #47b34f;"></i>
                    </div>
                </div>
            <?php }else{?>
                <div class="flex-item-8 invoice-default tr">
                    <?php echo "不需要发票"; ?>
                </div>
                <div class="flex-item-2 tr green">
                    修改<i class="iconfont" style="color: #47b34f;"></i>
                </div>
            <?php }?>
        </div>

    </a>


    <!--地址弹层-->
    <script id="invoice_ajax_Pop" type="text/html">
        <div class="w bdb tc lh44 bs-b clearfix pr">
            <a class="pa-tl" href="javascript:;" id="close_pop">
                <i class="aui-icon aui-icon-left green f28"></i>
            </a>
            <span class="f16">选择发票信息</span>
        </div>
        <div class="p10 which Invoicelist pa-t graybg" style="top: 44px; bottom: 50px; overflow-y: scroll;">
            <% for(var i=from; i<=to; i++) {%>
            <a class="item db w mt5 <%:=list[i].default%>" data-id="<%:=list[i].invoice_id%>" href="javascript:;">
                <div><p><em class="item-username"><%:=list[i].type_invoice%></em></p>
                    <p class="item-zone"><%:=list[i].title_invoice%></p>
                    <p class="item-address"><%:=list[i].code%></p>
                    <p class="item-address"><%:=list[i].address_and_phone%></p>
                    <p class="item-address"><%:=list[i].bank_and_account%></p>
                </div>
                <i class="iconfont cur-item"></i></a>
            <% } %>
        </div>
        <div class="fx-bottom p5 tc graybg bdt">
            <a class="btn mbtn w greenbtn" href="<?=\yii\helpers\Url::to(['/invoice/create','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>">创建新发票信息</a>
        </div>

    </script>
<script>
<?php $this->beginBlock('JS_END') ?>
    //选择地址
$("#invoice_pop").click(function(){
    $.showLoading("正在加载");
    $.post('<?= \yii\helpers\Url::to(['/invoice/my-invoices'], true) ?>',function(data){
        $.hideLoading();
        if(!data){
            location.href='<?=\yii\helpers\Url::to(['/invoice/create','redirect'=>Yii::$app->request->getAbsoluteUrl()])?>'
        }
        var addressPop=$('#invoice_ajax_Pop').html();
        var html= template(addressPop, {list:data,from:0,to:data.length-1});
        layer.open({
            type: 1,
            area: 'auto',
            style: 'position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px;',
            content:html
        });
    },'json');
});
$("body").on("click",'.Invoicelist .item',function(){
    var invoice_id=$(this).attr('data-id');

    $.showLoading("正在加载");
    $(this).addClass("cur").siblings().removeClass("cur");
    $.post('<?= \yii\helpers\Url::to('/checkout/select-invoice', true) ?>',{'invoice_id':invoice_id},function(data){
        $.hideLoading();
        if(data.result){
            $("#checkoutform-invoice_id").val(invoice_id);
            $(".invoice-default").html(data.html);
            layer.closeAll();
        }
    },'json');
});
$("body").on("click","#close_pop",function(){
    layer.closeAll();
});
<?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>