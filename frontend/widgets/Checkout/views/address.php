<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/21
 * Time: 16:22
 */
?>
<div id="shipping_address">
    <?=$input?>
        <?php if ($address_list) { ?>
    <ul class="address_list clearfix">
            <?php foreach ($address_list as $key => $address) { ?>
                <?php if ($address->address_id == $address_id) {
                    $css_curt = 'current';
                } else {
                    $css_curt = '';
                }?>
                <li class="<?=$css_curt?>" val="<?=$address->address_id?>">
                        <p class="f14"><?=$address->zone->name?>-<?=$address->citys->name?>
                            <span class="fb"><?=$address->district->name?></span></p>
                        <p class="line_dash mt5 mb5"></p>
                        <p class="address ">
                            <?php if($address->poiname && strpos($address->address_1, $address->poiname) !== false){ ?>
                                <?=$address->address_1?>
                            <?php }else{ ?>
                                <?=$address->poiaddress?><?=$address->poiname?><?=$address->address_1?>
                            <?php } ?>
                        </p>
                    <p class="clearfix fb"><?=$address->firstname?><span class="fr "><?=$address->telephone?> </span></p>

                    <p class="address_modify pa cp red none">
                        修改地址
                    </p>
                    <span class="address_flag pa"></span>
                </li>
            <?php } ?>
    </ul>
        <?php } ?>
    <span class="address_more vm cp " style="display: none">展开全部地址</span>
    <span class="btn btn_small vm grayBtn ml10 address_addBtn">使用新地址</span>
</div>
<?php $this->beginBlock('JS_END') ?>
//新增地址弹窗
$(".address_addBtn").on('click',function(){
$.ajax({
url: '<?=\yii\helpers\Url::to('/checkout/shipping-address',true)?>',
type: 'get',
dataType: 'html',
success: function(html) {
layer.open({type: 1,title: '创建地址',fix: true,shadeClose: false,area: ['500px', '410px'],content:html});
},
error: function(xhr, ajaxOptions, thrownError) {
alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
}
});
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>
