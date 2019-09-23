<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/10/23
 * Time: 9:16
 */
use \common\component\Helper\Datetime;
?>
<div class="tit1 mt15 mb5">
    <h2>请选择配送时间</h2>
</div>
<div class=" mb10 row tc lh200" id="delivery">
    <input type="hidden" name="CheckoutForm[delivery_date]" id="delivery_date" value="<?=$datas['data_date']?>">
    <input type="hidden" name="CheckoutForm[delivery_time]" id="delivery_time" value="<?=$datas['data_time']?>">

    <div class="col-4 p5">
        <div class="bd p5 whitebg cp delivery-time data-date="<?=$datas['data_date']?>" data-time="18:00-22:00">
             <p class="pb5 bdb green title" style="letter-spacing: 4px;">晚间送</p>
             <p class="pt5 pb5 f10 lh150">
                 <?=$datas['data_date']?></br>
                 18:00-22:00
             </p>
        </div>
    </div>
    <div class="col-4 p5">
        <div class="bd p5 whitebg cp delivery-time chooseTime">
            <p class="pb5 bdb blue- s_title" style="letter-spacing: 4px;">其它</p>
            <p class="pt5 pb5 f10 lh150 s_content">
                选择<br><!--限七天内-->
                限七天内
            </p>
        </div>
    </div>
</div>

<!--时间选择层-->
<div class="chooseTimePop whitebg pw70" style="display: none">
    <div class="p20">
        <div class="mb15">
            <span class="fb">配送日期：</span>
            <select class="p5" id="sp_date">
                <?php for($i=1;$i<=7;$i++){ ?>
                <?php if(time() >= strtotime('2016-01-29 00:00:00')  && time() <= strtotime('2016-02-06 17:00:00')){?>
                    <?php if($i <=1){?>
                        <option value="<?=date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datas['data_date'])))?>"><?=date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datas['data_date'])))?>&nbsp;&nbsp;<?=Datetime::getWeekDay(strtotime('+ '.$i.' day',strtotime($datas['data_date'])))?></option>
                    <?php }?>

                    <?php }elseif(time() <= strtotime('2016-02-06 17:00:00') && time() >= strtotime('2016-02-06 00:00:00')){?>

                <?php }else{?>
                        <?php if((!(date('Y-m-d H:i:s',strtotime('+ '.$i.' day',strtotime($datas['data_date']))) >= '2016-02-07' && date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datas['data_date']))) < '2016-02-14') ) || (date("Y-m-d H:i:s") >= '2015-02-06 17:00:00')){?>
                    <option value="<?=date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datas['data_date'])))?>"><?=date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datas['data_date'])))?>&nbsp;&nbsp;<?=Datetime::getWeekDay(strtotime('+ '.$i.' day',strtotime($datas['data_date'])))?></option>
                    <?php } ?>
                 <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="mb20">
            <span class="fb">配送时间：</span>
            <select class="p5" id="sp_time">
                <option value="08:00-12:00">08:00-12:00&nbsp;&nbsp;早间送</option>
                <option value="12:00-18:00">12:00-18:00&nbsp;&nbsp;午间送</option>
                <option value="18:00-22:00">18:00-22:00&nbsp;&nbsp;晚间送</option>
            </select>
        </div>
        <button type="button" class="btn mbtn greenbtn w chooseTimeOk">确定</button>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
$("#delivery .delivery-time").not('.chooseTime').click(function(){
$(this).parents("#delivery").find('.delivery-time').removeClass('cur');
$(this).addClass('cur');
$("#delivery_date").val($(this).attr('data-date'));
$("#delivery_time").val($(this).attr('data-time'));
});
$("#delivery").on('click','.chooseTime',function(){
var obj=$(this);
maskdiv($(".chooseTimePop"),"center");
$(".chooseTimeOk").click(function(){
$("#delivery_date").val($("#sp_date").val());
$("#delivery_time").val($("#sp_time").val());
obj.find('.s_content').html($("#sp_date").val()+"</br>"+$("#sp_time").val());
$(".chooseTimePop,.maskdiv").hide();
obj.parents("#delivery").find('.delivery-time').removeClass('cur');
obj.addClass('cur');
});
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
