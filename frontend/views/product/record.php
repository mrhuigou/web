<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/18
 * Time: 12:17
 */
use yii\widgets\LinkPager;
use common\component\Helper\Helper;
?>
<?php if($model){ ?>
<!--成交记录-->
<div class="summary-line">
    <label class="title">价&nbsp;格：</label>
    <span class="originPrice">¥<?=$data['price']?></span>
    <span class="greyTip">成交价格的不同可能是由于促销和打折引起的，详情可以咨询卖家。</span>
</div>
<table cellspacing="0" cellpadding="0" class="deal-record w">

    <tr>
        <th width="15%">买家</th>
        <th width="42%" align="left">规格/型号</th>
        <th width="12%">数量</th>
        <th width="15%">价格</th>
        <th>成交时间</th>
    </tr>
  <?php foreach($model as $key=>$val){ ?>
    <tr>
        <td><?=$val->order->telephone?Helper::str_mid_replace($val->order->telephone):'匿名'?></td>
        <td>
            <?php if($val->sku){
                foreach($val->sku as $sku_str){
                    list($option_name,$option_value)=explode(":",$sku_str);
                    ?>
                    <p title="<?=$sku_str?>"><?=$option_name?>：<span class="gray9"><?=$option_value?></span></p>
                <?php
                }
            }?>
        </td>
        <td align="center"><?=$val->quantity?></td>
        <td align="center"><span class="red fb">¥<?=number_format($val->total,2)?></span></td>
        <td class="tc">
            <p><?=date('Y-m-d',strtotime($val->order->date_added))?></p>
            <p class="gray9"><?=date('H:i:s',strtotime($val->order->date_added))?></p>
        </td>
    </tr>
  <?php } ?>
</table>
    <div class="tc p20"><?= LinkPager::widget(['pagination' => $pages]); ?></div>
    <?php $this->beginBlock('J_DealRecord') ?>
    $('#J_DealRecord .pagination a').live('click',function(){
    $.ajax({
    url:$(this).attr('href'),
    success:function(html){
    $('#J_DealRecord').html(html);
    }
    });
    return false;//阻止a标签
    });
    <?php $this->endBlock() ?>
    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['J_DealRecord'],\yii\web\View::POS_END);
    ?>
<?php }else{ ?>
    <!--没有记录时-->
    <div class="tc p20">Hi～最近还没有交易记录呢</div>
<?php } ?>
