<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/5/12
 * Time: 13:41
 */
use \common\component\Helper\Datetime;
?>
<style>
    .weui-popup-container{
        z-index: 9999;
    }
</style>
<a href="javascript:;" class="open-popup" data-target="#delivery_form_<?= $data['store_id'] ?>" id="delivery_title_<?= $data['store_id'] ?>"  data-source="<?= $data['store_id'] ?>">
    <div class="line-a flex-col w flex-middle delivery   p10 mt5" >
        <div class="flex-item-3">
            配送时间：
        </div>
        <div class="flex-item-7 delivery-default tr">
                每日惠购配送<br>
				<?= date('m-d', strtotime($data['method_date'])) ?>&nbsp;&nbsp;<?= Datetime::getWeekDay(strtotime($data['method_date'])) ?>&nbsp;&nbsp;<?= $data['method_time'] ?>
        </div>
        <div class="flex-item-2 tr green">
            修改<i class="iconfont" style="color: #47b34f;"></i>
        </div>
    </div>
<!--    <div class="line-a flex-col w flex-middle red mb5" >-->
<!--        <div class="flex-item-12">-->
<!--            因系统改造升级，<span class="fb">暂停晚间配送！</span>-->
<!--            给您带来的不便，我们深感抱歉！-->
<!--            请确定合适的配送时间，感谢您的理解！-->
<!--        </div>-->
<!---->
<!--    </div>-->
</a>
<!--基础形式及全屏-->
<div id="delivery_form_<?= $data['store_id'] ?>" data-source="<?= $data['store_id'] ?>" class="weui-popup-container">
    <div class="weui-popup-overlay"></div>
    <div class="weui-popup-modal">
        <div class="w bdb tc p10 lh200 f14 bg-wh ">选择配送时间</div>
        <div class="delivery-form">
            <input type="hidden" name="ViewDeliveryForm[delivery][<?= $data['store_id'] ?>][date]" class="delivery_date"
                   value="<?= $data['method_date'] ?>">
            <input type="hidden" name="ViewDeliveryForm[delivery][<?= $data['store_id'] ?>][time]" class="delivery_time"
                   value="<?= $data['method_time'] ?>">
            <div class="w  delivery-content">
                <div class="delivery-time-content" >
                    <h2 class="p5 bdb">配送时间</h2>
                    <div class="row">
					    <?php foreach (array_slice($data['method_times'],0,6) as $time) { ?>
                            <div class="col-3">
                                <div class="bd m5 p5 whitebg cp delivery-time <?= ($data['method_date']==$time['date'] && $data['method_time']==$time['time'])?"cur":""?>"
                                     data-date="<?= $time['date'] ?>" data-time="<?= $time['time'] ?>"
                                     data-source="<?= $data['store_id'] ?>">
                                    <p class="pb5 bdb <?=$time['css']?> title" style="letter-spacing: 4px;"><?= $time['label'] ?></p>
                                    <p class="pt5 pb5 f10 lh150">
									    <?= date('m-d', strtotime($time['date'])) ?>&nbsp;&nbsp;<?= Datetime::getWeekDay(strtotime($time['date'])) ?>
                                        <br>
									    <?= $time['time'] ?>
                                    </p>
                                </div>
                            </div>
					    <?php } ?>
                    </div>
<!--                    <div>-->
<!--                        <div class="col-12 bd m5 p5 whitebg cp f16 red">-->
<!--                            因系统改造升级，<span class="fb">暂停晚间配送！</span>-->
<!--                            给您带来的不便，我们深感抱歉！-->
<!--                            请确定合适的配送时间，感谢您的理解！-->
<!--                        </div>-->
<!--                    </div>-->

                </div>

            </div>
            <div class="fx-bottom p5 tc graybg bdt" style="z-index:9999">
                <a class="btn w lbtn greenbtn  confirm-delivery close-popup" href="javascript:;">确定</a>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
$("body").on("click",".delivery-time",function(){
$(this).parents(".delivery-time-content").find('.delivery-time').removeClass('cur');
$(this).addClass('cur');
$(this).parents(".weui-popup-modal").find(".delivery_date").val($(this).attr('data-date'));
$(this).parents(".weui-popup-modal").find(".delivery_time").val($(this).attr('data-time'));
});
$("body").on("click",".confirm-delivery",function(){
var store_id=$(this).parents(".weui-popup-container").attr('data-source');
var delivery_date=$(this).parents(".weui-popup-modal").find('.delivery_date').val();
var delivery_time=$(this).parents(".weui-popup-modal").find('.delivery_time').val();
var customer_coupon=$("#store_contain_"+store_id).find(".coupon-item:checked");
var customer_coupon_ids=[];
if(customer_coupon.length>0){
customer_coupon.each(function(){
customer_coupon_ids.push($(this).val());
});
}
var html="每日惠购配送<br/>";
html+=delivery_date+"&nbsp;&nbsp;"+delivery_time;
$("#delivery_title_"+store_id).find(".delivery-default").html(html);
$.showLoading("正在加载");
$.post('<?= \yii\helpers\Url::to('/checkout/order-ajax', true) ?>',{'store_id':store_id,'customer_coupon_id':customer_coupon_ids},function(data){
$.hideLoading();
if(data && data.status){
$("#store_contain_"+store_id).find(".store_totals").html(data.data);
$("#store_contain_"+store_id).find(".store-promotion").html(data.store_promotion);
var pay_total=0;
$(".total").each(function(){
pay_total=FloatAdd(pay_total,$(this).html());
});
$("#pay_total").html(pay_total);
}else{
$.alert(data.data);
}
},'json');
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>
