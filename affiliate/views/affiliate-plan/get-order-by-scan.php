


        <div class="weui-popup-overlay"></div>
        <div class="weui-popup-modal">

            <div class="flex-col tc bdb p10 lh200 f14 bg-wh">
                <a class="flex-item-2 close-popup" href="javascript:void (0);">
                    <i class="aui-icon aui-icon-left green f28"></i>
                </a>
                <div class="flex-item-8 f16">
                    自提订单明细
                </div>
                <div class="flex-item-2">

                </div>
            </div>
            <div class="delivery-form">
                <?php foreach ($order->orderProducts as $orderProduct) { ?>
                <div class="flex-col tc p5 graybg" style="border-bottom: 1px dotted #999;">
                    <div class="flex-item-3">
                        <img src="<?=\common\component\image\Image::resize($orderProduct->product->image, 100, 100)?> " class="db w">
                    </div>
                        <div class="flex-item-7 tl pl10">
                            <h2><?= $orderProduct->name?></h2>
                            <p class="gray9  mt2"><?=$orderProduct->product->getSku()?></p>
                        </div>
                        <div class="flex-item-2 tc flex-middle flex-row">
                            <p class="blue mb5"> x<?= $orderProduct->quantity?></p><p class="red  fb">￥<?=$orderProduct->price?></p>
                        </div>
                    </div>
                        <?php }?>
                <?php if($order->orderShipping){?>
                    <div class="fool-addr bdb p15 mt5 clearfix whitebg mb10">
                        <div class="fl w f12 lh150">
                            <p class="f14 mb5 clearfix">
                                <span class="fr">电话: <?php echo $order->orderShipping->shipping_telephone;?></span>
                                <span class="f14">收货人:<?php echo $order->orderShipping->shipping_firstname;?></span>
                            </p>
                        </div>
                    </div>

                <?php }?>


                <div class="fx-bottom p10 tc bg-wh bdt" style="z-index:9999">
                    <a class="btn w lbtn greenbtn  confirm-delivery" id="confirm_self_take" data-key="<?php echo $type;?>"  data-content="<?php echo $scan_string;?>" href="javascript:;">确定</a>
                </div>
            </div>
        </div>





