<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/5/5
 * Time: 17:32
 */
use \common\component\Helper\Datetime;
$this->context->layout = "main-iframe";
?>
<div class="chooseTimePop whitebg w300">
    <div class="p20">
        <div class="mb15">
            <span class="fb">配送日期：</span>
            <select class="p5 sp_date">
                <?php for($i=1;$i<=7;$i++){ ?>
                    <?php if(date('Y-m-d',strtotime('+ '.$i.' day',strtotime($date)))=='2016-09-15'){
                        continue;
                    }?>
                    <option value="<?=date('Y-m-d',strtotime('+ '.$i.' day',strtotime($date)))?>"><?=date('Y-m-d',strtotime('+ '.$i.' day',strtotime($date)))?>&nbsp;&nbsp;<?=Datetime::getWeekDay(strtotime('+ '.$i.' day',strtotime($date)))?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb20">
            <span class="fb">配送时间：</span>
            <select class="p5 sp_time">
                <option value="08:00-13:00">08:00-13:00&nbsp;&nbsp;早间</option>
                <option value="13:00-18:00">13:00-18:00&nbsp;&nbsp;午间</option>
                <option value="18:00-22:00">18:00-22:00&nbsp;&nbsp;晚间</option>
            </select>
        </div>
        <button type="button" class="btn mbtn greenbtn w chooseTimeOk">确定</button>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
$(".chooseTimeOk").on('click',function(){
var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
var store_id='<?=$store_id?>';
var delivery_date=$(this).parents(".chooseTimePop").find(".sp_date").val();
var delivery_time=$(this).parents(".chooseTimePop").find(".sp_time").val();
parent.$("#store_contain_"+store_id).find('.delivery_date').val(delivery_date);
parent.$("#store_contain_"+store_id).find('.delivery_time').val(delivery_time);
var date=new Date(delivery_date);
var str=eval("'" + date.pattern("MM-dd EE") + "'")+"</br>"+delivery_time;
parent.$("#store_contain_"+store_id).find('.chooseTime .s_content').html(str);
parent.$("#store_contain_"+store_id).find('.delivery-time').removeClass('cur');
parent.$("#store_contain_"+store_id).find('.chooseTime').addClass('cur');
parent.AsyUpdateShopTotals(store_id);
parent.layer.close(index);
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
