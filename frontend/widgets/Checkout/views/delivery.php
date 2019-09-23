<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/19
 * Time: 15:39
 */
use \common\component\Helper\Datetime;
?>
<div class="store_delivery_content" data-source="<?=$data['store_id']?>">
<input type="hidden" name="CheckoutForm[delivery][<?=$data['store_id']?>][date]" class="delivery_date" value="<?=$data['method_date']?>">
<input type="hidden" name="CheckoutForm[delivery][<?=$data['store_id']?>][time]" class="delivery_time" value="<?=$data['method_time']?>">
    <?php if($data['method_times']){ ?>
    <div class="delivery_time_items" >
        <h2 class="fb mt15">配送时间</h2>
        <div class="delivery-con mb10 row tc lh200">
	        <?php foreach (array_slice($data['method_times'],0,6) as $time) { ?>
                <div class="col-3">
                    <div class="bd m5 p5 whitebg cp delivery-time <?= ($data['method_date']==$time['date'] && $data['method_time']==$time['time'])?"cur":""?>"
                         data-date="<?= $time['date'] ?>" data-time="<?= $time['time'] ?>"
                         data-source="<?= $data['store_id'] ?>">
                        <p class="pb5 bdb <?= $time['css'] ?> title" style="letter-spacing: 4px;"><?= $time['label'] ?></p>
                        <p class="pt5 pb5 f10 lh150">
					        <?= date('m-d', strtotime($time['date'])) ?>&nbsp;&nbsp;<?= Datetime::getWeekDay(strtotime($time['date'])) ?>
                            <br>
					        <?= $time['time'] ?>
                        </p>
                    </div>
                </div>
	        <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
<?php $this->beginBlock('JS_END') ?>
$(".store_delivery_content .delivery-time").not('.chooseTime').click(function(){
var store_id=$(this).attr('data-source');
$(this).parents(".store_delivery_content .delivery-con").find('.delivery-time').removeClass('cur');
$(this).addClass('cur');
$("#store_contain_"+store_id).find('.delivery_date').val($(this).attr('data-date'));
$("#store_contain_"+store_id).find('.delivery_time').val($(this).attr('data-time'));
AsyUpdateShopTotals(store_id);
});
$(".delivery_type_items").each(function(){
var self = $(this).children("a").first();
if(self.attr('data-content')!='any'){
self.parents(".store_delivery_content").find('.delivery_time_items').show();
}
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
