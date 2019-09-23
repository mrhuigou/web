<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='发票信息';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  pb50">
    <div id="address_list" class="pb50">
    <?php if($my_invoices){ ?>
       <?php foreach($my_invoices as $value){ ?>
            <div class="flex-col flex-center store-item bdb  whitebg p5 item-address <?php //echo $value->default?"red":""?>">
                <label class="label-checkbox item-content flex-item-1 flex-row flex-middle flex-center">
                    <input type="radio" value="<?=$value->invoice_id?>" name="address_id"  class="item" <?php //echo $value->default?'checked':""?>>
                    <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                </label>
            <div class="flex-item-9 ">
                <?php
                    if($value->type_invoice == 2){
                        $invoice_type = '企业增值税普通发票';
                    }elseif($value->type_invoice == 3){
                        $invoice_type = '企业增值税专用发票';
                    }else{
                        $invoice_type = '个人发票';
                    }
                ?>
                <p><span class="fb"><?php echo $invoice_type?></span><em class="ml10"></em></p>
                <p><?=$value->title_invoice?$value->title_invoice:""?></p>
                <?php if($value->type_invoice == 2 || $value->type_invoice == 3){?>
                    <?=$value->code?>
               <?php }?>

           <?php if($value->type_invoice == 3){?>
                <p> <?=$value->address_and_phone?></p>
                <p>  <?=$value->bank_and_account?></p>
           <?php }?>
            </div>
            <div class="flex-item-2 flex-row flex-middle flex-center">
                <a href="<?=\yii\helpers\Url::to(['/invoice/update','id'=>$value->invoice_id,'redirect'=>Yii::$app->request->getAbsoluteUrl() ])?>" class="iconfont gray9 ">&#xe615;</a>
            </div>
            </div>
        <?php } ?>
    <?php }else{ ?>
        <p class="tc p20"> 您还没有添加过发票！</p>
    <?php } ?>
    </div>
</section>
<div class="fx-bottom  bdt whitebg p10 w tc ">
    <?php  if(\Yii::$app->request->get("redirect")){?>
        <a class="btn mbtn greenbtn mr5 " id="confirm_invocie" href="javascript:;">确认选择</a>
    <?php } ?>
    <a  class="btn mbtn bluebtn"   href="<?=\yii\helpers\Url::to(['/invoice/create','redirect'=>Yii::$app->request->getAbsoluteUrl()],true)?>" >添加新发票</a>
</div>
<script>
<?php $this->beginBlock('JS_END') ?>
$("#address_list  .item-address").click(function(){
    $(this).addClass('red').siblings().removeClass('red');
    $(this).find('input:radio').attr('checked',true);
});
$("#confirm_invocie").on("click",function(){
    if($("input[type='radio']:checked").val()){
        $.post('/checkout/select-invoice',{invoice_id:$("input[type='radio']:checked").val()},function(data){
            if(data.result){
                <?php if($redirect=Yii::$app->request->get('redirect')){?>
                location.href="<?=$redirect?>";
                <?php }else{?>
                location.href="/checkout/index";
                <?php }?>
            }
        },'json');
    }else{
        alert("请选择一个地址作为收货地址");
    }
});
<?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
