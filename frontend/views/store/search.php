<?php echo $header;?>
<?php $this->title = $model->name.'所有商品';?>
<?php  $storeThemeColumn = $model->theme->storeThemeColumn;?>
<div class="grid-s5m0">
    <div id="content">
    <div class="w1100 bc">
        <div class="clearfix">
            <div class="w210 fl">
                <!--商家信息-->
                <div class="whitebg">
                    <h2 class="p5 pl10 f14 shop_i_tit">商家信息</h2>

                    <div class="whitebg p10">
                        <p class="bd_dashB pb10 mb10">
                            <span class="fb">店铺动态评分</span> 与同行业相比<br/>
                            描述相符：<span class="shops_i_rate fb"><?php echo number_format($review_score['rating'],2); ?></span><i
                                class="shops_i_compare white brs ml10 mr10"> 满分 </i><i
                                class="shops_i_percent fb">5</i><br/>
                            服务态度：<span class="shops_i_rate fb"><?php echo number_format($review_score['service'],2); ?></span><i
                                class="shops_i_compare white brs ml10 mr10"> 满分 </i><i
                                class="shops_i_percent fb">5</i><br/>
                            物流服务：<span class="shops_i_rate fb"><?php echo number_format($review_score['delivery'],2); ?></span><i
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
                        <?php if(isset($productbase)&& $productbase) { ?>
                            <div class="item4line1">
                                <?php foreach($productbase as $key=>$product){  ?>
                                    <dl class="item <?=$key%4==3?"last":""?> mb10"  >
                                        <dt class="photo">
                                            <a  href="<?= yii\helpers\Url::to(['product/index','shop_code'=>$product['store_code'],'product_base_code'=>$product['product_code']],true)?>" target="_blank" >

                                                <img class="lazy" src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($product['image'],500,500)?>" alt="<?=$product['text_name']?>" />
                                            </a>
                                        </dt>
                                        <?php if($product['sub_image']) { ?>
                                            <dd class="thumb">

                                                <!--包装-->
                                                <div class="picScroll-left-list example1">
                                                    <div class="hd clearfix">
                                                        <a class="next">></a>
                                                        <a class="prev"><</a>
                                                    </div>
                                                    <div class="bd">
                                                        <ul class="picList clearfix">
                                                            <?php foreach($product['sub_image'] as $sub){ ?>
                                                                <li>
                                                                    <div class="pic"><a href="javascript:;"data-sku="<?=$sub->sku?>" for="<?php echo $sub->getPrice()?>">

                                                                            <img class="lazy" src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($sub->image,500,500)?>" /></a></div>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </dd>
                                        <?php } ?>
                                        <dd class="detail">
                                            <a class="item-name"  href="<?=yii\helpers\Url::to(['/product/index','shop_code'=>$product['store_code'],'product_base_code'=>$product['product_code']],true)?>" target="_blank" ><?=$product['product_name']?> </a>
                                            <div class="attribute">
                                                <div class="cprice-area"><span class="symbol">¥</span><span class="c-price"><?=number_format($product['price'],2);?></span></div>
                                            </div>
                                        </dd>
                                    </dl>
                                <?php } ?>
                            </div>
                            <div class="tc p20"><?= \yii\widgets\LinkPager::widget(['pagination' => $pages,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页']); ?></div>
                        <?php } else{ ?>
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

    <?php $this->beginBlock("JS_Block")?>
        
            jQuery(".picScroll-left-list").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",scroll:5,vis:5,trigger:"click"});
            <!--包装-->
            $(".picScroll-left-list.example1").each(function(){
                var len=$(this).find("li").length;
                if(len>5){
                    $(this).find(".hd").show()
                }
            }); 

    <?php $this->endBlock()?>

    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);