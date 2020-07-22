<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
$this->title = '订单确认';
?>
<style type="text/css">
    .plat-box{
        width: 96%;
        margin: 0 auto;
        border-top: 1px solid #47b34f;
        border-left: 3px solid #47b34f;
        border-right: 3px solid #47b34f;
        border-bottom: 3px solid #47b34f;
        margin-bottom: 20px;
    }
    .plat-title{
        background-color: #47b34f;
        color: #FFFFFF;
        height: 45px;
        line-height: 45px;
        width: 100%;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
    }
</style>
<header class="header">
    <div class="flex-col tc">
        <a class="flex-item-2" href="javascript:history.back()">
            <i class="aui-icon aui-icon-left green f28"></i>
        </a>
        <div class="flex-item-8 f16">
			<?= Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/cart/index']) ?>">
            <i class="aui-icon aui-icon-cart green f28"></i>
        </a>
    </div>
</header>
<div class="pb50">
    <section class="veiwport pl5 pr5 pt5">
		<?php if (Yii::$app->getSession()->getFlash('error')) { ?>
            <div class=" p10  tc bd w redbg mb5">
                <p class="white"><?php echo Yii::$app->getSession()->getFlash('error') ?></p>
            </div>
		<?php } ?>
		<?php $form = ActiveForm::begin(['id' => 'form-checkout', 'fieldConfig' => [
			'template' => "<div class=\"mt5  clearfix\">{input}</div>{error}",
			'inputOptions' => ["class" => 'appbtn tl w'],
			'errorOptions' => ['class' => 'red fb tc db error']
		],]); ?>
		<?= $form->field($model, 'address_id')->widget(\h5\widgets\Checkout\Address::className()); ?>
        <?= $form->field($model, 'invoice_id')->widget(\h5\widgets\Checkout\Invoice::className()); ?>
        <?php $is_jiarun = false;?>
        <?php if ($isOrderMerge) { ?>
            <!--      Merge      -->
            <div class="plat-box">
                <div class="plat-title"><?= $platformName ?></div>
                <?= h5\widgets\Checkout\Delivery::widget(['store_id' => $mergeStoreId, 'total' => $mergeTotals['total']]) ?>
                <?php foreach ($mergeList as $k => $val) { ?>
                    <?php if($val['base']->store_id == 1){ $is_jiarun = true;}?>
                    <div class="store_contain whitebg " id="store_contain_<?= $val['base']->store_id ?>">
                        <!--     店铺title           -->
                        <div class="mt5">
                            <h2 class="clearfix p10">
                                <span class="fl ">店铺：<em class="org"><?= $val['base']->name ?></em></span>
                                <span class="fr red"><?php if ($val['base']->befreepostage == 1) { //是否包邮（满X元包邮）
                                        if ($val['base']->minbookcash > 0) {
                                            echo '店铺订单金额满' . $val['base']->minbookcash . '元包邮';
                                        } else {
                                            echo '店铺包邮';
                                        }
                                    }
                                    ?></span>
                            </h2>
                        </div>
                        <!--     商品列表           -->
                        <?php foreach ($val['products'] as $key => $value) { ?>
                            <div class="flex-col tc p5 graybg" style="border-bottom: 1px dotted #999;">
                                <div class="flex-item-3">
                                    <a href="<?= \yii\helpers\Url::to(['product/index', 'product_code' => $value->product->product_code, 'shop_code' => $value->product->store_code]) ?>">
                                        <img src="<?= \common\component\image\Image::resize($value->product->image, 100, 100) ?>"
                                             class="db w">
                                    </a>
                                </div>
                                <div class="flex-item-7 tl pl10">
                                    <h2><?= $value->product->description->name ?></h2>
                                    <p class="gray9  mt2"><?= $value->product->getSku() ?></p>
                                    <?= h5\widgets\Checkout\Promotion::widget(['promotion' => $value->getPromotion(), 'qty' => $value->quantity]) ?>
                                </div>
                                <div class="flex-item-2 tc flex-middle flex-row">
                                    <p class="blue mb5"> x<?= $value->quantity ?></p>
                                    <p class="red  fb">￥<?= $value->getCost() ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="store-promotion  mb5"><?= h5\widgets\Checkout\StorePromotion::widget(['promotion' => $val['promotion'], 'coupon_gift' => $val['coupon_gift']]) ?></div>
                        <?php if($is_jiarun){?>
                            <?php $shipping = 0;$sub_total = 0;?>
                            <?php if ($val['totals']) {
                                foreach ($val['totals'] as $value) {
                                    if($value['code'] == 'shipping'){
                                        $shipping = $value['value'];
                                    }
                                    if($value['code'] == 'sub_total'){
                                        $sub_total = $value['value'];
                                    }
                                }

                            } ?>
                            <?= h5\widgets\Checkout\Coupon::widget(['store_id' => $val['base']->store_id, 'product' => $val['products'],'sub_total' => $sub_total,'shipping' => $shipping]) ?>
                            <?php if (count($checkout_ad) >0) { ?>
                                <div id="shipping_script" hidden>
                                    <?php if( count($checkout_ad) >0 ){?>
                                        <div id="layerCon" style="display: none;">

                                            <div class="layer0" style="padding: 5px;">
                                                <h2 class="f14 row-two-max mb10">
                                                    <span class="btn btn-xxs btn-bd-red">免邮</span>
                                                    订单金额满<?php echo $val['base']->minbookcash;?>元包邮，您还差<i class="red" id="diff_free"><?php echo bcsub($val['base']->minbookcash,$sub_total,2)?></i>元,即可以享受包邮！</h2>

                                                <div class="flex-col"  <?= (count($checkout_ad) == 1)? "style=\"text-align: center\"" : ""?>>
                                                    <?php
                                                    switch (count($checkout_ad)){
                                                        case  1: $items = 12;break;
                                                        case  2: $items = 6;break;
                                                        case  3: $items = 4;break;
                                                        case  4: $items = 3;break;
                                                        default: $items = 6;break;
                                                    }
                                                    ?>
                                                    <?php foreach ($checkout_ad as $ad){?>
                                                        <a href="<?= \yii\helpers\Url::to($ad->link_url, true) ?>" class="flex-item-<?php echo $items?>">
                                                            <img src="<?= \common\component\image\Image::resize($ad->source_url) ?>" class="w bd m2"  <?= (count($checkout_ad) == 1)? "style=\"width:180px\"" : ""?>>
                                                        </a>
                                                    <?php }?>

                                                </div>
                                                <!-- 关闭按钮 -->
                                                <a class="layer-close iconfont" href="javascript:;">&#xe612;</a>

                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                        <?php }else{ ?>
                            <?= h5\widgets\Checkout\Coupon::widget(['store_id' => $val['base']->store_id, 'product' => $val['products']]) ?>
                        <?php } ?>
                        <!--  每笔订单底部明细 总额-金额-运费-总计  -->
                        <div class="graybg p10 store_totals">
                            <?php if ($val['totals']) { ?>
                                <?php foreach ($val['totals'] as $value) { ?>
                                    <?php   if($value['code'] == 'shipping'){?>

                                    <?php }else{?>
                                        <?php if($value['code']=='sub_total'){?>
                                            <p class="mb5 clearfix lh150">
                                                <span class="fr red fb">￥<em class="<?=$value['code']?>"><?=$value['value']?></em></span>
                                                <span class="fl fb"><?=$value['title']?>：</span>
                                            </p>
                                            <p class="mb5 clearfix lh150">
                                                <span class="fr red fb">￥<em class="m-order-total"><?=$value['value']?></em></span>
                                                <span class="fl fb">订单金额：</span>
                                            </p>

                                        <?php }else{?>

                                        <?php }?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>

                    </div>
                <?php } ?>

                <!--  合单后 邮费 and 总额     -->
                <div class="graybg p10 store_totals">
                    <div style="border:1px dashed #000;margin-top: 30px;"></div>
                    <div class="p5" id="free_return" style="display: none">
                        <?php if( count($checkout_ad) < 1){?>
                            <a class="btn mbtn greenbtn-bd tc w" href="/read-more/index">满68包邮，去凑单</a>
                        <?php }else{?>
                            <a class="btn mbtn greenbtn-bd tc w layerTri" href="javascript:void(0)">订单金额满68元免运费，去凑单</a>
                        <?php }?>

                    </div>
                    <p class="mb5 clearfix lh150">
                        <span class="fr red fb">￥<em class="shopping merge-spt"><?= $mergeTotals['shopping'] ?></em></span>
                        <span class="fl fb">邮费：</span>
                    </p>
                    <!--            <p class="mb5 clearfix lh150 total_line" id = "--><?php //if($value['code'] =='points'){ echo 'point_customer_total_'.$value['customer_code_id'];}?><!--" >-->
                    <p class="mb5 clearfix lh150 total_line" id = "point_customer_total_1" >
                        <span class="fr red fb">￥<em class="total merge-totals"><?= $mergeTotals['total'] ?> </em></span>
                        <span class="fl fb">实付总计：</span>
                    </p>
                    <div class="mt5 mb50">
                        <input type="text" class="w p10 gray6 bd" name="CheckoutForm[comment][merge]" placeholder="备注：如有特殊需求请填写" maxlength="50">
                    </div>
                </div>

            </div>
            <!--      Merge  other    -->
            <?php if (!empty($noMergeList)) { ?>
                <?php foreach ($noMergeList as $k => $val) { ?>
                    <?php if($val['base']->store_id == 1){ $is_jiarun = true;}?>
                    <?= h5\widgets\Checkout\Delivery::widget(['store_id' => $val['base']->store_id, 'total' => $val['total']]) ?>
                    <div class="store_contain whitebg " id="store_contain_<?= $val['base']->store_id ?>">
                        <!--     店铺title           -->
                        <div class="mt5">
                            <h2 class="clearfix p10">
                                <span class="fl ">店铺：<em class="org"><?= $val['base']->name ?></em></span>
                                <span class="fr red"><?php if ($val['base']->befreepostage == 1) { //是否包邮（满X元包邮）
                                        if ($val['base']->minbookcash > 0) {
                                            echo '店铺订单金额满' . $val['base']->minbookcash . '元包邮';
                                        } else {
                                            echo '店铺包邮';
                                        }
                                    }
                                    ?></span>
                            </h2>
                        </div>
                        <!--     商品列表           -->
                        <?php foreach ($val['products'] as $key => $value) { ?>
                            <div class="flex-col tc p5 graybg" style="border-bottom: 1px dotted #999;">
                                <div class="flex-item-3">
                                    <a href="<?= \yii\helpers\Url::to(['product/index', 'product_code' => $value->product->product_code, 'shop_code' => $value->product->store_code]) ?>">
                                        <img src="<?= \common\component\image\Image::resize($value->product->image, 100, 100) ?>"
                                             class="db w">
                                    </a>
                                </div>
                                <div class="flex-item-7 tl pl10">
                                    <h2><?= $value->product->description->name ?></h2>
                                    <p class="gray9  mt2"><?= $value->product->getSku() ?></p>
                                    <?= h5\widgets\Checkout\Promotion::widget(['promotion' => $value->getPromotion(), 'qty' => $value->quantity]) ?>
                                </div>
                                <div class="flex-item-2 tc flex-middle flex-row">
                                    <p class="blue mb5"> x<?= $value->quantity ?></p>
                                    <p class="red  fb">￥<?= $value->getCost() ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="store-promotion  mb5"><?= h5\widgets\Checkout\StorePromotion::widget(['promotion' => $val['promotion'], 'coupon_gift' => $val['coupon_gift']]) ?></div>
                        <?php if($is_jiarun){?>
                            <?php $shipping = 0;$sub_total = 0;?>
                            <?php if ($val['totals']) {
                                foreach ($val['totals'] as $value) {
                                    if($value['code'] == 'shipping'){
                                        $shipping = $value['value'];
                                    }
                                    if($value['code'] == 'sub_total'){
                                        $sub_total = $value['value'];
                                    }
                                }

                            } ?>
                            <?= h5\widgets\Checkout\Coupon::widget(['store_id' => $val['base']->store_id, 'product' => $val['products'],'sub_total' => $sub_total,'shipping' => $shipping]) ?>
                            <?php if (count($checkout_ad) >0) { ?>
                                <div id="shipping_script" hidden>
                                    <?php if( count($checkout_ad) >0 ){?>
                                        <div id="layerCon" style="display: none;">

                                            <div class="layer0" style="padding: 5px;">
                                                <h2 class="f14 row-two-max mb10">
                                                    <span class="btn btn-xxs btn-bd-red">免邮</span>
                                                    订单金额满<?php echo $val['base']->minbookcash;?>元包邮，您还差<i class="red" id="diff_free"><?php echo bcsub($val['base']->minbookcash,$sub_total,2)?></i>元,即可以享受包邮！</h2>

                                                <div class="flex-col"  <?= (count($checkout_ad) == 1)? "style=\"text-align: center\"" : ""?>>
                                                    <?php
                                                    switch (count($checkout_ad)){
                                                        case  1: $items = 12;break;
                                                        case  2: $items = 6;break;
                                                        case  3: $items = 4;break;
                                                        case  4: $items = 3;break;
                                                        default: $items = 6;break;
                                                    }
                                                    ?>
                                                    <?php foreach ($checkout_ad as $ad){?>
                                                        <a href="<?= \yii\helpers\Url::to($ad->link_url, true) ?>" class="flex-item-<?php echo $items?>">
                                                            <img src="<?= \common\component\image\Image::resize($ad->source_url) ?>" class="w bd m2"  <?= (count($checkout_ad) == 1)? "style=\"width:180px\"" : ""?>>
                                                        </a>
                                                    <?php }?>

                                                </div>
                                                <!-- 关闭按钮 -->
                                                <a class="layer-close iconfont" href="javascript:;">&#xe612;</a>

                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                        <?php }else{ ?>
                            <?= h5\widgets\Checkout\Coupon::widget(['store_id' => $val['base']->store_id, 'product' => $val['products']]) ?>
                        <?php } ?>
                        <!--  每笔订单底部明细 总额-金额-运费-总计  -->
                        <div class="graybg p10 store_totals">
                            <?php if ($val['totals']) { ?>
                                <?php foreach ($val['totals'] as $value) { ?>
                                    <?php   if($value['code'] == 'shipping'){?>
                                        <div style="border:1px dashed #000;margin-top: 30px;"></div>

                                        <p class="mb5 clearfix lh150">
                                            <span class="fr red fb">￥<em class="<?=$value['code']?>"><?=$value['value']?></em></span>
                                            <span class="fl fb"><?=$value['title']?>：</span>
                                        </p>

                                    <?php }else{?>
                                        <?php if($value['code']=='sub_total'){?>
                                            <p class="mb5 clearfix lh150">
                                                <span class="fr red fb">￥<em class="<?=$value['code']?>"><?=$value['value']?></em></span>
                                                <span class="fl fb"><?=$value['title']?>：</span>
                                            </p>
                                            <p class="mb5 clearfix lh150">
                                                <span class="fr red fb">￥<em><?=$value['value']?></em></span>
                                                <span class="fl fb">订单金额：</span>
                                            </p>
                                            <div class="p5" id="free_return" style="display: none">
                                                <?php if( count($checkout_ad) < 1){?>
                                                    <a class="btn mbtn greenbtn-bd tc w" href="/read-more/index">满68包邮，去凑单</a>
                                                <?php }else{?>
                                                    <a class="btn mbtn greenbtn-bd tc w layerTri" href="javascript:void(0)">订单金额满68元免运费，去凑单</a>
                                                <?php }?>

                                            </div>
                                        <?php }else{?>
                                            <p class="mb5 clearfix lh150 total_line" id = "<?php if($value['code'] =='points'){ echo 'point_customer_total_'.$value['customer_code_id'];}?>" >
                                    <span class="fr red fb">￥<em
                                                class="<?= $value['code'] ?>"><?= $value['value'] ?></em></span>
                                                <span class="fl fb"><?= $value['title'] ?>：</span>
                                            </p>
                                        <?php }?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="mt5 mb50">
                        <input type="text" class="w p10 gray6 bd" name="CheckoutForm[comment][<?= $k ?>]" placeholder="备注：如有特殊需求请填写" maxlength="50">
                    </div>
                <?php } ?>
            <?php } ?>

        <?php } else{ ?>
            <!--     No Merge      -->
            <?php foreach ($cart as $k => $val) { ?>
                <?php if($val['base']->store_id == 1){ $is_jiarun = true;}?>
                <?= h5\widgets\Checkout\Delivery::widget(['store_id' => $val['base']->store_id, 'total' => $val['total']]) ?>
                <div class="store_contain whitebg " id="store_contain_<?= $val['base']->store_id ?>">
                    <!--     店铺title           -->
                    <div class="mt5">
                        <h2 class="clearfix p10">
                            <span class="fl ">店铺：<em class="org"><?= $val['base']->name ?></em></span>
                            <span class="fr red"><?php if ($val['base']->befreepostage == 1) { //是否包邮（满X元包邮）
                                    if ($val['base']->minbookcash > 0) {
                                        echo '店铺订单金额满' . $val['base']->minbookcash . '元包邮';
                                    } else {
                                        echo '店铺包邮';
                                    }
                                }
                                ?></span>
                        </h2>
                    </div>
                    <!--     商品列表           -->
                    <?php foreach ($val['products'] as $key => $value) { ?>
                        <div class="flex-col tc p5 graybg" style="border-bottom: 1px dotted #999;">
                            <div class="flex-item-3">
                                <a href="<?= \yii\helpers\Url::to(['product/index', 'product_code' => $value->product->product_code, 'shop_code' => $value->product->store_code]) ?>">
                                    <img src="<?= \common\component\image\Image::resize($value->product->image, 100, 100) ?>"
                                         class="db w">
                                </a>
                            </div>
                            <div class="flex-item-7 tl pl10">
                                <h2><?= $value->product->description->name ?></h2>
                                <p class="gray9  mt2"><?= $value->product->getSku() ?></p>
                                <?= h5\widgets\Checkout\Promotion::widget(['promotion' => $value->getPromotion(), 'qty' => $value->quantity]) ?>
                            </div>
                            <div class="flex-item-2 tc flex-middle flex-row">
                                <p class="blue mb5"> x<?= $value->quantity ?></p>
                                <p class="red  fb">￥<?= $value->getCost() ?></p>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="store-promotion  mb5"><?= h5\widgets\Checkout\StorePromotion::widget(['promotion' => $val['promotion'], 'coupon_gift' => $val['coupon_gift']]) ?></div>
                    <?php if($is_jiarun){?>
                        <?php $shipping = 0;$sub_total = 0;?>
                        <?php if ($val['totals']) {
                            foreach ($val['totals'] as $value) {
                                if($value['code'] == 'shipping'){
                                    $shipping = $value['value'];
                                }
                                if($value['code'] == 'sub_total'){
                                    $sub_total = $value['value'];
                                }
                            }

                        } ?>
                        <?= h5\widgets\Checkout\Coupon::widget(['store_id' => $val['base']->store_id, 'product' => $val['products'],'sub_total' => $sub_total,'shipping' => $shipping]) ?>
                        <?php if (count($checkout_ad) >0) { ?>
                            <div id="shipping_script" hidden>
                                <?php if( count($checkout_ad) >0 ){?>
                                    <div id="layerCon" style="display: none;">

                                        <div class="layer0" style="padding: 5px;">
                                            <h2 class="f14 row-two-max mb10">
                                                <span class="btn btn-xxs btn-bd-red">免邮</span>
                                                订单金额满<?php echo $val['base']->minbookcash;?>元包邮，您还差<i class="red" id="diff_free"><?php echo bcsub($val['base']->minbookcash,$sub_total,2)?></i>元,即可以享受包邮！</h2>

                                            <div class="flex-col"  <?= (count($checkout_ad) == 1)? "style=\"text-align: center\"" : ""?>>
                                                <?php
                                                switch (count($checkout_ad)){
                                                    case  1: $items = 12;break;
                                                    case  2: $items = 6;break;
                                                    case  3: $items = 4;break;
                                                    case  4: $items = 3;break;
                                                    default: $items = 6;break;
                                                }
                                                ?>
                                                <?php foreach ($checkout_ad as $ad){?>
                                                    <a href="<?= \yii\helpers\Url::to($ad->link_url, true) ?>" class="flex-item-<?php echo $items?>">
                                                        <img src="<?= \common\component\image\Image::resize($ad->source_url) ?>" class="w bd m2"  <?= (count($checkout_ad) == 1)? "style=\"width:180px\"" : ""?>>
                                                    </a>
                                                <?php }?>

                                            </div>
                                            <!-- 关闭按钮 -->
                                            <a class="layer-close iconfont" href="javascript:;">&#xe612;</a>

                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                    <?php }else{ ?>
                        <?= h5\widgets\Checkout\Coupon::widget(['store_id' => $val['base']->store_id, 'product' => $val['products']]) ?>
                    <?php } ?>
                    <!--  每笔订单底部明细 总额-金额-运费-总计  -->
                    <div class="graybg p10 store_totals">
                        <?php if ($val['totals']) { ?>
                            <?php foreach ($val['totals'] as $value) { ?>
                                <?php   if($value['code'] == 'shipping'){?>
                                    <div style="border:1px dashed #000;margin-top: 30px;"></div>

                                    <p class="mb5 clearfix lh150">
                                        <span class="fr red fb">￥<em class="<?=$value['code']?>"><?=$value['value']?></em></span>
                                        <span class="fl fb"><?=$value['title']?>：</span>
                                    </p>

                                <?php }else{?>
                                    <?php if($value['code']=='sub_total'){?>
                                        <p class="mb5 clearfix lh150">
                                            <span class="fr red fb">￥<em class="<?=$value['code']?>"><?=$value['value']?></em></span>
                                            <span class="fl fb"><?=$value['title']?>：</span>
                                        </p>
                                        <p class="mb5 clearfix lh150">
                                            <span class="fr red fb">￥<em><?=$value['value']?></em></span>
                                            <span class="fl fb">订单金额：</span>
                                        </p>
                                        <div class="p5" id="free_return" style="display: none">
                                            <?php if( count($checkout_ad) < 1){?>
                                                <a class="btn mbtn greenbtn-bd tc w" href="/read-more/index">满68包邮，去凑单</a>
                                            <?php }else{?>
                                                <a class="btn mbtn greenbtn-bd tc w layerTri" href="javascript:void(0)">订单金额满68元免运费，去凑单</a>
                                            <?php }?>

                                        </div>
                                    <?php }else{?>
                                        <p class="mb5 clearfix lh150 total_line" id = "<?php if($value['code'] =='points'){ echo 'point_customer_total_'.$value['customer_code_id'];}?>" >
                                    <span class="fr red fb">￥<em
                                                class="<?= $value['code'] ?>"><?= $value['value'] ?></em></span>
                                            <span class="fl fb"><?= $value['title'] ?>：</span>
                                        </p>
                                    <?php }?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>

                </div>
                <div class="mt5 mb50">
                    <input type="text" class="w p10 gray6 bd" name="CheckoutForm[comment][<?= $k ?>]" placeholder="备注：如有特殊需求请填写" maxlength="50">
                </div>
            <?php } ?>
        <?php } ?>





		<?php ActiveForm::end(); ?>
    </section>
</div>
<div class="fx-bottom flex-col bdt whitebg ">
    <div class="flex-item-8 flex-row flex-middle flex-center  p15  ">
        <p> 应付：<span class="red f16 fb">￥<em id="pay_total"><?= $pay_total ?></em></span></p>
    </div>
    <a id="button_submit" href="javascript:;"
       class="flex-item-4 flex-row flex-middle db flex-center tc fb  white p15  greenbg" style="line-height: 22px;">
        提交结算
    </a>
</div>
<div id="confirm_form_order" class="bg-f0" style="display: none;">
    <div class="   m5 ">
        <div id="confirm_form_shippingtime" class="flex-col w flex-middle bg-green white p10 ">
        </div>
    </div>

    <?php if(time() >= strtotime('2020-02-14 23:00:00') && time() <= strtotime('2020-02-15 11:30:00')){?>
        <div class="col-12 bd m5 p5 whitebg cp f18 red">
            受雨雪影响配送受限,订单最早于2月16日8:00-12:00送达,敬请谅解!
        </div>
    <?php }else{?>
        <div class="col-12 bd m5 p5 whitebg cp f18 red">
            安全你、我、他，无接触配送，请到小区门口或楼下收货，共抗疫情，配送小哥不进入楼宇，如果小区放行，我们非常高兴送到家里，敬请谅解！
        </div>
    <?php }?>

    <div class="flex-col">
        <a class="flex-item-6 tc red fb p15 bg-wh" href="javascript:;" id="confirm_cannel">去修改</a>
        <a class="flex-item-6 tc greenbg white fb p15 " href="javascript:;" id="confirm_pay">去支付</a>
    </div>
</div>
<script>
<?php $this->beginBlock('JS_END') ?>
$(".btn_points").click(function(){
    var _this = $(this);
    var point_customer_id = _this.attr('data-date');
    var store_id = _this.attr('data-content');
    $(".pop-tips").fadeIn();
    $.showLoading();
    $.ajax({
        url:'<?php echo \yii\helpers\Url::to(['checkout/points'])?>',
        data:'point_customer_id='+point_customer_id + '&store_id='+store_id,
        type : "post",
        dataType : "json",
        success:function(res){
            //$(".pop-tips").fadeOut();
            window.location.reload();
//            if(res.action == 'add'){
//                var insertHtml = '<p id="point_customer_total_'+res.point_customer_id +'" class="mb5 clearfix lh150 total_line">';
//                insertHtml += '<span class="fr red fb">￥ <em class="point_customer">'+res.value+'</em></span>';
//                insertHtml +=  ' <span class="fl fb">'+ res.point_name +'：</span>';
//                insertHtml += ' </p>';
//                $('#store_contain_'+ store_id+' .store_totals .total_line').eq(-2).after(insertHtml);
//                _this.find('.btn_balance_html').html('（取消使用）');
//            }else{
//                $('#store_contain_'+ store_id+' .store_totals ').find('#point_customer_total_'+res.point_customer_id).remove();
//                _this.find('.btn_balance_html').html('（点击使用）');
//
//            }

        }
    });
});
$("#button_submit").click(function(){
    if(!$("#checkoutform-address_id").val()){
        $.alert("请创建收货地址！");
    }
    console.log($("#checkoutform-address_id").val());
    $("#confirm_form_address").html($(".select_address").html());
    var delivery_list="";
    $(".delivery-default").each(function(){
        delivery_list+='<div class="flex-item-4 mb5">配送时间：</div><div class="flex-item-8  tr mb5">'+$(this).html()+'</div>';
    });
    $("#confirm_form_shippingtime").html(delivery_list);
    maskdiv($("#confirm_form_order"),"bottom");
});
$("#confirm_cannel").click(function(e){
    e.stopPropagation();
    $("#confirm_form_order").slideUp();
    $(".maskdiv").remove();
    $(".content").scrollTop(0);
});
//ost_flag=true;
$("#confirm_pay").click(function(){
    if(post_flag){
        post_flag=false;
        $.showLoading("正在提交");

        $('#form-checkout').submit();
        $.hideLoading();
        $('.error').each(function () {
            if(!$(this).is(':hidden')){
                $("#confirm_form_order").slideUp();
                $(".maskdiv").remove();
            }
        })

    }



});
$(".layerTri").click(function(){
    layer.open({
        type: 1,
        closeBtn: 2,
        title: false,
        shadeClose: true,
        shade:0.3,
        content: $('#layerCon').html(),
       // btn: ['去结算']
    });
    $(".layer-close").click(function(){
        layer.closeAll();
    })
});

$("body").on('click','.layerTri', function () { layer.open({
    type: 1,
    closeBtn: 2,
    title: false,
    shadeClose: true,
    shade:0.3,
    content: $('#layerCon').html(),
    // btn: ['去结算']
});
    $(".layer-close").click(function(){
        layer.closeAll();
    })  });

// Ad_Sys_Code();
<?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>
