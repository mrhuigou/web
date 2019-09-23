<?php echo $header; ?>
<?php $this->title = $model->name . '-首页'; ?>
<?php $storeThemeColumn = $model->theme->storeThemeColumn; ?>
<div class="grid-s5m0">
    <?php if (isset($storeThemeColumn) && !empty($storeThemeColumn)) { ?>
        <div class="bannerBox" style="height:500px;">
            <div class="boxShow">
                <ul>
                    <?php foreach ($storeThemeColumn as $v) { ?>
                        <?php if (strtoupper($v->theme_column_code) == strtoupper('bannershow1')) { ?>
                            <?php foreach ($v->storeThemeColumnInfo as $banners) {

                                if ($banners->image) {
                                    $image = \common\component\image\Image::resize($banners->image);
                                } else {
                                    $image = \common\component\image\Image::resize("no_image_home.jpg");
                                }

                                ?>
                                <li class="w h">
                                    <a href="<?php echo $banners->url; ?>" class="boxShow_a w h"
                                       style="background:url('<?php echo $image; ?>') no-repeat center top;"></a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
            <div class="menu" style="right:45%;"></div>
        </div>
    <?php } ?>

    <div class="shopsbg pt10">
        <div class="w1100 bc">
            <!--热销商品-->

            <?php if (isset($storeThemeColumn) && !empty($storeThemeColumn)) { ?>
                <div class="shops_rx" id="hot_sale">
                    <div class="foucsBox clearfix" id="foucsBox1">
                        <ul class="imgCon clearfix">
                            <?php foreach ($storeThemeColumn as $v) { ?>
                                <?php if ($v->theme_column_code == 'hotsale1' && $v->theme_column_type == 'PRODUCT') { ?>

                                    <li class="clearfix">
                                        <?php foreach ($v->storeThemeColumnInfo as $key => $product) { ?>

                                            <?php if ($key < 5) { ?>
                                                <?php
                                                $product_info = \api\models\V1\Product::findOne(['product_id' => $product->product_id]);

                                                if (!empty($product_info)) { ?>
                                                    <?php
                                                    if (isset($product_info->image)) {
                                                        if ($product_info->image) {
                                                            $image = \common\component\image\Image::resize($product_info->image, 187, 225);
                                                        } else {
                                                            $image = \common\component\image\Image::resize("no_image_home.jpg", 187, 225);
                                                        }
                                                    }
                                                    ?>
                                                    <div class="shops_rxlist">
                                                        <a href="<?php echo yii\helpers\Url::to(['product/index', 'shop_code' => $product_info->store_code, 'product_base_code' => $product_info->product_base_code]); ?>">
                                                            <img src="<?php echo $image; ?>"
                                                                 alt="<?php echo $product_info->description->name; ?>"
                                                                 width="187" height="225"/></a>
                                                        <a href="<?php echo yii\helpers\Url::to(['product/index', 'shop_code' => $product_info->store_code, 'product_base_code' => $product_info->product_base_code]); ?>"
                                                           class="db mb5 mxh20"><?php echo $product_info->description->name; ?></a>
                                                        <i class="s_tprice f18">￥<?php echo number_format($product_info->getPrice(), 2, '.', ''); ?></i>
                                                        <i class="del gray6 f14">￥<?php echo number_format($product_info->price, 2, '.', '') ?></i>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>

                                    </li>
                                    <?php if (count($v->storeThemeColumnInfo) > 5) { ?>
                                        <li class="clearfix" id="second">
                                            <?php $num = 0; ?>
                                            <?php foreach ($v->storeThemeColumnInfo as $key => $product) { ?>
                                                <?php if ($key >= 5 && $key < 10) {
                                                    $num++;
                                                    $product_info = \api\models\V1\Product::findOne(['product_id' => $product->product_id]);
                                                    ?>
                                                    <?php if (!empty($product_info)) { ?>
                                                        <?php
                                                        if (isset($product_info->image)) {
                                                            if ($product_info->image) {
                                                                $image = \common\component\image\Image::resize($product_info->image, 187, 225);
                                                            } else {
                                                                $image = \common\component\image\Image::resize("no_image_home.jpg", 187, 225);
                                                            }
                                                        }
                                                        ?>

                                                        <div class="shops_rxlist">
                                                            <a href="<?php echo yii\helpers\Url::to(['product/index', 'shop_code' => $product_info->store_code, 'product_base_code' => $product_info->product_base_code]); ?>">
                                                                <img src="<?php echo $image; ?>"
                                                                     alt="<?php echo $product_info->description->name; ?>"
                                                                     width="187" height="225"/></a>
                                                            <a href="<?php echo yii\helpers\Url::to(['product/index', 'shop_code' => $product_info->store_code, 'product_base_code' => $product_info->product_base_code]); ?>"
                                                               class="db mb5 mxh20"><?php echo $product_info->description->name; ?></a>
                                                            <i class="s_tprice f18">￥<?php echo number_format($product_info->getPrice(), 2, '.', ''); ?></i>
                                                            <i class="del gray6 f14">￥<?php echo number_format($product_info->price, 2, '.', '') ?></i>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </li>

                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>

                        </ul>
                        <div class="foucs"></div>
                    </div>
                </div>
            <?php } ?>

            <!--促销活动-->
            <?php if (isset($storeThemeColumn) && !empty($storeThemeColumn)) { ?>
                <div class="foucsBox clearfix mt10" id="foucsBox2" style="width:1100px;height:auto;">
                    <ul class="imgCon clearfix">

                        <?php foreach ($storeThemeColumn as $v) { ?>
                            <?php if ($v->theme_column_code == 'promotion1' && $v->theme_column_type == 'ADS') { ?>
                                <?php foreach ($v->storeThemeColumnInfo as $banners) {

                                    if ($banners->image) {
                                        $image = \common\component\image\Image::resize($banners->image);
                                    } else {
                                        $image = \common\component\image\Image::resize("no_image_home.jpg");
                                    }
                                    ?>
                                    <li class="clearfix" style="width:1100px;height:auto;">
                                        <a href="<?php echo $banners->url; ?>"><img src="<?php echo $image; ?>"
                                                                                    alt="<?php echo $banners->title ? $banners->title : '广告图片'; ?>"
                                                                                    width="1100" height="255"
                                                                                    class="db"/></a>
                                    </li>
                                <?php } ?>

                            <?php } ?>
                        <?php } ?>

                    </ul>
                    <div class="foucs"></div>
                </div>

            <?php } ?>
            <?php if (isset($storeThemeColumn) && !empty($storeThemeColumn)) { ?>
                <div class="clearfix mt10 mb10 shops_cx" id="productlist">
                    <?php foreach ($storeThemeColumn as $v) { ?>
                        <?php if ($v->theme_column_code == 'promotionproduct' && $v->theme_column_type == 'PRODUCT') { ?>

                            <?php foreach ($v->storeThemeColumnInfo as $key => $product) { ?>
                                <?php if ($key < 5) {
                                    $product_info = \api\models\V1\Product::findOne(['product_id' => $product->product_id]);
                                    ?>
                                    <?php if (!empty($product_info)) { ?>

                                        <?php
                                        if (isset($product_info->image)) {
                                            if ($product_info->image) {
                                                $image = \common\component\image\Image::resize($product_info->image, 187, 225);
                                            } else {
                                                $image = \common\component\image\Image::resize("no_image_home.jpg", 187, 225);
                                            }
                                        }
                                        ?>


                                        <div class="shops_rxlist pr <?php if ($key == 4) {
                                            echo 'last';
                                        } ?>">
                                            <!--tag-->
                                            <span class="s_cIion"></span>
                                            <a href="<?php echo yii\helpers\Url::to(['product/index', 'shop_code' => $product_info->store_code, 'product_base_code' => $product_info->product_base_code]); ?>">
                                                <img src="<?php echo $image; ?>"
                                                     alt="<?php echo $product_info->description->name; ?>" width="187"
                                                     height="225"/></a>
                                            <a href="<?php echo yii\helpers\Url::to(['product/index', 'shop_code' => $product_info->store_code, 'product_base_code' => $product_info->product_base_code]); ?>"
                                               class="db mb5 mxh20"><?php echo $product_info->description->name; ?></a>
                                            <i class="s_tprice f18">￥<?php echo number_format($product_info->getPrice(), 2, '.', ''); ?></i>
                                            <i class="del gray6 f14">￥<?php echo number_format($product_info->price, 2, '.', '') ?></i>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>


                        <?php } ?>
                    <?php } ?>

                </div>

            <?php } ?>
            <div class="clearfix">
                <div class="w210 fl">
                    <!--商家信息-->
                    <div class="whitebg">
                        <h2 class="p5 pl10 f14 shop_i_tit">商家信息</h2>

                        <div class="whitebg p10">
                            <p class="bd_dashB pb10 mb10">
                                <span class="fb">店铺动态评分</span> 与同行业相比<br/>
                                描述相符：<span
                                    class="shops_i_rate fb"><?php echo number_format($review_score['rating'], 2); ?></span><i
                                    class="shops_i_compare white brs ml10 mr10"> 满分 </i><i
                                    class="shops_i_percent fb">5</i><br/>
                                服务态度：<span
                                    class="shops_i_rate fb"><?php echo number_format($review_score['service'], 2); ?></span><i
                                    class="shops_i_compare white brs ml10 mr10"> 满分 </i><i
                                    class="shops_i_percent fb">5</i><br/>
                                物流服务：<span
                                    class="shops_i_rate fb"><?php echo number_format($review_score['delivery'], 2); ?></span><i
                                    class="shops_i_compare white brs ml10 mr10"> 满分 </i><i
                                    class="shops_i_percent fb">5</i><br/>

                            </p>
                            <dl class="clearfix">
                                <dt class="fl w60 tr">公 司 名：</dt>
                                <dd class="fl w120"><?php echo $model->name; ?></dd>
                            </dl>
                            <dl class="clearfix">
                                <dt class="fl w60 tr">所 在 地：</dt>
                                <?php if ($model->city) {
                                    $city = \api\models\V1\City::findOne(['city_id' => $model->city]);
                                    $district = \api\models\V1\District::findOne(['district_id' => $model->district]);
                                    if (!empty($city)) { ?>
                                        <dd class="fl w120"><?php if (isset($city)) {
                                                echo $city->name;
                                            }
                                            if (isset($district)) {
                                                echo $district->name;
                                            } ?></dd>
                                    <?php }
                                } ?>


                            </dl>
                            <dl class="clearfix">
                                <dt class="fl w60 tr">商 &nbsp; &nbsp; 家：</dt>
                                <?php
                                if ($model->store_type == 'NORMAL') {
                                    $store_type = '精品店';
                                } elseif ($model->store_type == 'MARKET') {
                                    $store_type = '市场店';
                                } elseif ($model->store_type == 'FLAGSHIP') {
                                    $store_type = '旗舰店';
                                } else {
                                    $store_type = '市场店';
                                }
                                ?>
                                <dd class="fl w120"><?php if (isset($store_type)) {
                                        echo $store_type;
                                    } ?></dd>
                            </dl>

                        </div>
                    </div>

                    <!--商品分类-->
                    <?php if (isset($store_category) && !empty($store_category)) { ?>
                        <div class="whitebg" id="category_list">
                            <h2 class="p5 pl10 f14 shop_i_tit">商品分类</h2>

                            <?php foreach ($store_category as $top_category) { ?>

                                <dl class="shop_sidebar">
                                    <dt><i>-</i><a
                                            href="<?php echo yii\helpers\Url::to(['store/search', "shop_code" => $model->store_code, "store_cate_code" => $top_category['category_store_code']]); ?>"><?php echo $top_category['name'] ?></a>
                                    </dt>
                                    <?php if (isset($top_category['children']) && !empty($top_category['children'])) { ?>
                                        <?php foreach ($top_category['children'] as $sec_category) { ?>
                                            <dd><i>&middot;</i><a
                                                    href="<?php echo yii\helpers\Url::to(["store/search", "shop_code" => $model->store_code, "store_cate_code" => $top_category['category_store_code']]); ?>"><?php echo $sec_category['name'] ?></a>
                                            </dd>
                                        <?php } ?>
                                    <?php } ?>
                                </dl>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <!--品牌介绍-->

                    <?php if (isset($storeThemeColumn) && !empty($storeThemeColumn)) { ?>


                        <?php foreach ($storeThemeColumn as $v) { ?>
                            <?php if ($v->theme_column_code == 'store_description' && $v->theme_column_type == 'HTML') { ?>
                                <?php foreach ($v->storeThemeColumnInfo as $content) {

                                    ?>
                                    <div class="whitebg">
                                        <h2 class="p5 pl10 f14 shop_i_tit">店铺介绍</h2>

                                        <p class="p10 t2 lh200"><?php echo $content->contents ?></p>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>


                    <?php } ?>


                </div>
                <div class="w880 fr">
                    <h2 class="p5 pl10 pr15 f14 shop_i_tit"><a
                            href="<?php echo yii\helpers\Url::to(["store/search", "shop_code" => $model->store_code]); ?>"
                            class="fr white f12">更多商品&gt;</a>RECOMMENDED &nbsp; 商品推荐</h2>
                    <!--商品列表-->   <!------------------------------------------>
                    <div class="tshop-pbsm-tmall-srch-list">
                        <div class="J_TItems">
                            <?php if (isset($productbase) && $productbase) { ?>
                                <div class="item4line1">
                                    <?php foreach ($productbase as $key => $product) { ?>
                                        <dl class="item <?= $key % 4 == 3 ? "last" : "" ?> mb10">
                                            <dt class="photo">
                                                <a href="<?= yii\helpers\Url::to(['product/index', 'shop_code' => $product['store_code'], 'product_base_code' => $product['product_code']], true) ?>"
                                                   target="_blank">

                                                    <img class="lazy" src="/assets/images/placeholder.png"
                                                         data-url="<?= \common\component\image\Image::resize($product['image'], 500, 500) ?>"
                                                         alt="<?= $product['text_name'] ?>"/>
                                                </a>
                                            </dt>
                                            <?php if ($product['sub_image']) { ?>
                                                <dd class="thumb">

                                                    <!--包装-->
                                                    <div class="picScroll-left-list example1">
                                                        <div class="hd clearfix">
                                                            <a class="next">></a>
                                                            <a class="prev"><</a>
                                                        </div>
                                                        <div class="bd">
                                                            <ul class="picList clearfix">
                                                                <?php foreach ($product['sub_image'] as $sub) { ?>
                                                                    <li>
                                                                        <div class="pic"><a href="javascript:;"
                                                                                            data-sku="<?= $sub->sku ?>"
                                                                                            for="<?php echo $sub->getPrice() ?>">

                                                                                <img class="lazy"
                                                                                     src="/assets/images/placeholder.png"
                                                                                     data-url="<?= \common\component\image\Image::resize($sub->image, 500, 500) ?>"/></a>
                                                                        </div>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </dd>
                                            <?php } ?>
                                            <dd class="detail">
                                                <a class="item-name"
                                                   href="<?= yii\helpers\Url::to(['/product/index', 'shop_code' => $product['store_code'], 'product_base_code' => $product['product_code']], true) ?>"
                                                   target="_blank"><?= $product['product_name'] ?> </a>

                                                <div class="attribute">
                                                    <div class="cprice-area"><span class="symbol">¥</span><span
                                                            class="c-price"><?= number_format($product['price'], 2); ?></span>
                                                    </div>
                                                </div>
                                            </dd>
                                        </dl>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <div class="tc p20">Hi～没有找到相关商品！</div>
                            <?php } ?>
                        </div>
                    </div>
                    <!------------------------------------------>

                    <!--翻页-->

                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$this->registerJsFile("/assets/script/boxShow.js", ['depends' => [\frontend\assets\AppAsset::className()]]);
$this->registerJsFile("/assets/script/foucsbox.js", ['depends' => [\frontend\assets\AppAsset::className()]]);

?>
<?php $this->beginBlock('JS_END') ?>
var leng = $('.bannerBox ul>li').length;
if (leng <= 0) {
$(".bannerBox").hide();
}

var hot_leng = $('#foucsBox1 ul>li').length;
if (hot_leng <= 0) {
$("#hot_sale").hide();
}

var ad1_leng = $('#foucsBox2 ul>li').length;
if (ad1_leng <= 0) {
$("#foucsBox2").hide();
}


//幻灯片
bannerSlider(".bannerBox");
//内容播放
lmk123("#foucsBox1");
lmk123("#foucsBox2",8000);

/*sidebar*/
$(".shop_sidebar dt i").toggle(function(){
$(this).text('+');
$(this).parent().siblings("dd").slideUp();
},function(){
$(this).text('-');
$(this).parent().siblings("dd").slideDown();
});

/*商品列表边框*/
$(".shops_rxlist2:nth-child(4n)").css("marginRight","0");

jQuery(".picScroll-left-list").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",scroll:5,vis:5,trigger:"click"});
<!--包装-->
$(".picScroll-left-list.example1").each(function(){
var len=$(this).find("li").length;
if(len>5){
$(this).find(".hd").show()
}
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_END);

?>
