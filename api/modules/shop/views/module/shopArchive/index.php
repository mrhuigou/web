<?php if(isset($data['data']) && $data['data']) { ?>

<div id="J_DcShopArchive" class="J_DcAsyn">
    <div id="side-shop-info" data-spm="1997427133">
        <div class="shop-intro">
            <h3 class="hd" style="">
                <div class="name">
                    <a  class="shopLink" href="#" target="_blank"><?=$data['data']['store_name']?>店铺</a>
                </div>
                <i></i>
            </h3><div style="height: 0px; margin-top: 0px; margin-bottom: 0px; overflow: hidden; width: 108px;"></div>
            <div class="main-info">
                <div class="shopdsr-item">
                    <div class="shopdsr-title">描 述</div>
                    <div class="shopdsr-score shopdsr-score-up-ctrl">
                        <span class="shopdsr-score-con">4.8</span>
                        <s class="shopdsr-score-up"></s>
                    </div>
                </div>

                <div class="shopdsr-item">
                    <div class="shopdsr-title">服 务</div>
                    <div class="shopdsr-score shopdsr-score-up-ctrl">
                        <span class="shopdsr-score-con">4.8</span>
                        <s class="shopdsr-score-up"></s>
                    </div>
                </div>

                <div class="shopdsr-item">
                    <div class="shopdsr-title">物 流</div>
                    <div class="shopdsr-score shopdsr-score-up-ctrl">
                        <span class="shopdsr-score-con">4.8</span>
                        <s class="shopdsr-score-up"></s>
                    </div>
                </div>
            </div>
            <div class="btnArea">
                <a  href="<?=$data['data']['url']?>" target="_blank" class="enterShop">进店逛逛</a>
                <a id="xshop_collection_href" href="#" class="J_PopupTrigger collection xshop_sc J_TDialogTrigger J_TokenSign favShop">收藏店铺</a>
            </div>
        </div>
    </div>
</div>
<?php } ?>