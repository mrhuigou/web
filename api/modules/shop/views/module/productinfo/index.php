<?php
use yii\helpers\Url;
if(isset($data['data'])){ ?>
<style>
    .itagList{display:none;}
</style>
<div id="J_TabBarBox" style="width: 788px;">
    <ul id="J_TabBar" class="tabbar tm-clear ">
        <li class="tm-selected"><a href="#description" rel="nofollow" hidefocus="true" data-index="0">商品详情</a></li>
        <li><a href="#J_Reviews" rel="nofollow" hidefocus="true" data-index="1">累计评价 <em class="J_ReviewsCount" style="display: inline;"><?=$data['data']['review_count']?></em></a></li>
        <li><a href="#J_DealRecord" rel="nofollow" hidefocus="true" data-index="2">月成交记录<span class="J_MonSales" style="display: inline;"><em class="J_TDealCount tm-MRswitchRecord"><?=$data['data']['sale_count']?></em>件</span></a></li>
        <li class="tm-shop-list" ></li>
        <li class="tm-qrcode-icon"><a class="tm-qr-togger">手机购买</a><img class="tm-qrcode-pic" src="<?=Url::to(['/shop/img','product_base_code'=>$data['data']['base']->product_base_code])?>" >
        </li>
    </ul>

</div>
<div id="itagCon">
    <div id="description" class="itagList" style="display: block;">
        <?php if($data['data']['attributes']) { ?>
        <div id="attributes" class="attributes">
            <div class="attributes-list" id="J_AttrList">
                <p class="attr-list-hd tm-clear none"><em>产品参数：</em></p>
                <ul id="J_AttrUL">
                    <?php  foreach($data['data']['attributes'] as $value ){ ?>
                    <li title="&nbsp;<?=$value['text']?>"><?=$value['name']?>:&nbsp;<?=$value['text']?></li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <?php }?>
        <div  class="J_DetailSection tshop-psm tshop-psm-bdetaildes">
            <h4 class="hd">商品详情</h4>
            <div class="content ke-post" style="height: auto;">
                <?=\yii\helpers\Html::decode($data['data']['description']->description)?>
            </div>
        </div>
    </div>
    <div id="J_Reviews" class="itagList">
        <?=$data['data']['reviews']?>
    </div>
    <div id="J_DealRecord" class="itagList">
        <?=$data['data']['records']?>
    </div>
</div>
<div class="tabbar-bg" style="display: none;"></div>
<div style="height: 0px; margin-top: 0px; margin-bottom: 0px; overflow: hidden; width: 790px;"></div>
<?php }else{ ?>
<img src="http://192.168.1.224:8084/images/product_info.jpg">
<?php } ?>