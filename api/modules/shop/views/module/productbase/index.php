<?php
if(isset($data['data'])&&$data['data']) { ?>
<div id="detail">
    <div id="J_DetailMeta" class="tm-detail-meta tm-clear">
        <div class="tm-clear">
            <div class="tb-property">
                <div class="tb-wrap">
                    <div class="tb-detail-hd">
                        <h1 data-spm="1000983">
                           <?php if ($data['data']['base']->bepresell) {?> <span class="red">[预售]</span><?php } ?><?=$data['data']['description']->name?>
                        </h1>
                        <p>
                            <?=$data['data']['description']->meta_description?>
                        </p>
                    </div>
                    <!--引入normalBasic-->
                    <div class="tm-fcs-panel">
                        <?php if($data['data']['promotion']) { ?>
                        <dl class="tm-shopPromo-panel">
                            <div class="tm-shopPromotion-title tm-gold">
                                <dt class="tb-metatit">优惠</dt><dd><?=$data['data']['description']->name?>促销活动</dd><a class="more">更多优惠<s></s></a>
                            </div>
                            <div class="tm-floater-Box  hidden">
                                <div class="floater ">
                                    <div class="hd">
                                        <em class="title">优惠</em><?=$data['data']['description']->name?>促销活动<a class="more unmore">收起<s></s></a>
                                        <a class="more">更多优惠<s></s></a>
                                    </div>
                                    <ul class="bd">
                                        <?php foreach($data['data']['promotion'] as $list){ ?>
                                        <li class="noCoupon" data-index="0">
                                                <p> <?=$list['title']?></p>
                                                 <p>   购买规格
                                                    <em style="color: #38b">【<?=implode(",",$list['sku_names'])?>】</em>
                                                    <?php echo $list['amount']?$list['amount']:'';?>
                                                    <?php echo $list['quantity']?$list['quantity']:'';?>
                                                    促销价：
                                                    <?php if($list['price_type']=='UNITPRICE'){ ?>
                                                        <em class="red bold">¥<?=number_format($list['price'],2)?></em>元
                                                    <?php }else{ ?>
                                                        <em class="red bold">¥<?=$list['rebate']*10?></em>折
                                                    <?php }?>
                                                 </p>
                                                    <?php if($list['gifts']){
                                                        foreach($list['gifts'] as $gift){
                                                            ?>
                                                            <p>
                                                            <?php if($gift['price']>0){?>
                                                                加 <em class="red">¥<?=number_format($gift['price'],2)?>元</em>,
                                                            <?php } ?>
                                                            赠：<a href="<?=$gift['url']?>" class="all"><?=$gift['name']?>【<?=implode(",",$gift['sku_names'])?>】</a>
                                                            赠送数量：买<?=$gift['base_number']?>送<?=$gift['quantity']?>
                                                            </p>
                                                        <?php } }?>
                                        </li>
                                         <?php } ?>
                                    </ul>
                                    <div class="ft">
                                        <span class="title"><a class="all" target="_blank" href="">所有活动商品</a></span>
                                    </div>
                                </div>
                            </div>
                        </dl>
                        <?php } ?>
                        <dl class="tm-promo-panel tm-promo-cur" id="J_PromoPrice" data-label="促销">
                            <dt class="tb-metatit">会员价</dt>
                            <dd>
                                <div class="tm-promo-price">
                                    <em class="tm-yen">¥</em> <span class="tm-price"><?=$data['data']['vip_price']?></span>
                                    <em class="tm-promo-type "><s></s>会员价</em>
                                    &nbsp;&nbsp;
                                </div>
                                <p>   </p>
                            </dd>
                        </dl>
                        <dl class="tm-price-panel" id="J_StrPriceModBox">
                            <dt class="tb-metatit">市场价</dt>
                            <dd><em class="tm-yen">¥</em> <span class="tm-price"><?=$data['data']['price']?></span></dd>
                        </dl>
                    </div>
                    <ul class="tm-ind-panel">
                        <li class="tm-ind-item tm-ind-sellCount canClick" data-label="月销量"><div class="tm-indcon"><span class="tm-label">月销量</span><span class="tm-count"><?=$data['data']['sale_count']?></span></div></li>
                        <li class="tm-ind-item tm-ind-reviewCount canClick tm-line3" id="J_ItemRates"><div class="tm-indcon"><span class="tm-label">累计评价</span><span class="tm-count"><?=$data['data']['review_count']?></span></div></li>
                        <li class="tm-ind-item tm-ind-emPointCount" ><div class="tm-indcon"><a href="http://vip.tmall.com/vip/index.htm" target="_blank"><span class="tm-label">送积分</span><span class="tm-count">0</span></a></div></li>
                    </ul>
                    <div class="tb-key">
                        <div class="tb-skin">
                            <div class="tb-sku">
                                <?php if ($data['data']['base']->spec_array) {
                                foreach($data['data']['base']->spec_array as $sku ) { ?>
                                <dl class="tb-prop tm-sale-prop tm-clear ">
                                    <dt class="tb-metatit"><?=$sku['name']?></dt>
                                    <dd>
                                        <ul data-property="<?=$sku['name']?>" class="tm-clear J_TSaleProp  ">
                                            <?php foreach($sku['content'] as $content) { ?>
                                            <li class="sku" attr_id="<?=$content['value']?>"><a href="javascript:;"><span><?=$content['name']?></span></a><i>已选中</i></li>
                                            <?php } ?>
                                        </ul>
                                    </dd>
                                </dl>
                                <?php
                                }
                                }
                                ?>
                                <dl class="tb-amount tm-clear">
                                    <dt class="tb-metatit">数量</dt>
                                    <dd id="J_Amount">
                                        <span class="tb-amount-widget mui-amount-wrap">
                                        <input type="text" class="tb-text mui-amount-input" value="1" maxlength="8" title="请输入购买量">
                                        <span class="mui-amount-btn">
                                            <span class="mui-amount-increase">&#xe614;</span>
                                            <span class="mui-amount-decrease">&#xe615;</span>
                                        </span>
                                        <span class="mui-amount-unit">件</span>
                                        </span>
                                        <em id="J_EmStock" class="tb-hidden" style="display: inline;">库存<?=$data['data']['stock_count']?>件</em>
                                        <span id="J_StockTips"></span>
                                    </dd>
                                </dl>


                                <div class="tb-action tm-clear">
                                    <?php if( $data['data']['base']['beintoinv']==0){ ?>
                                        <div class="tb-btn-buy tb-btn-sku">
                                            <a  href="javascript:;" rel="nofollow"  title="此商品已下架！">此商品已下架！</a>
                                        </div>
                                    <?php } else{ ?>
                                    <div class="tb-btn-buy tb-btn-sku">
                                        <a id="J_LinkBuy" href="javascript:;" rel="nofollow"  title="点击此按钮，到下一步确认购买信息。">立刻购买<span class="ensureText">确认</span></a>
                                    </div>
                                    <div class="tb-btn-basket tb-btn-sku "><a href="javascript:;" rel="nofollow" id="J_LinkBasket"><i>&#xe612;</i>加入购物车<span class="ensureText">确认</span></a></div>
                                    <div class="tb-btn-add tb-btn-sku tb-hidden"><a href="javascript:;" rel="nofollow" id="J_LinkAdd"><i>&#xe612;</i>加入购物车</a></div>
                                     <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tm-ser tm-clear ">
                        <dl class="tm-clear">
                            <dt class="tb-metatit">服务承诺</dt>
                            <dd class="tm-laysku-dd">
                                <ul class="tb-serPromise">
                                    <li data-spm="0"><a href="#" title="按时发货" target="_blank">
                                            按时发货
                                        </a></li>
                                    <li><a href="#" title="售后无忧是天猫为T4 天猫会员提供的专享售后服务特权，让您购物更省心。" target="_blank">
                                            <s>T4</s>                                    售后无忧
                                        </a></li>
                                    <li> <a href="#" title="七天无理由退换" target="_blank">
                                            七天无理由退换
                                        </a></li>
                                </ul>
                            </dd>
                        </dl>
                    </div>


                </div>
            </div>
            <div class="tb-gallery">
                <div class="tb-booth">
                    <span class="zoomIcon" style="">&#xf012c;</span>
                    <a href="javascript:;" rel="nofollow" target="_blank">
                   <img id="J_ImgBooth"  data-ks-imagezoom="<?='http://img1.365jiarun.com/'.$data['data']['base']->image?>"
                    alt="<?=$data['data']['description']->name?>" src="<?='http://img1.365jiarun.com/'.$data['data']['base']->image?>" >
                    </a>
                </div>
                <?php if ($data['data']['images']) { ?>
                <ul id="J_UlThumb" class="tb-thumb tm-clear">
                    <?php foreach($data['data']['images'] as $image){ ?>
                    <li>
                        <a href="javascript:;">
                            <img  data-bigimagewidth="700"  data-bigimageheight="700"   data-has-zoom="true"  data-ks-imagezoom="<?='http://img1.365jiarun.com/'.$image['image']?>"
                                    src="<?='http://img1.365jiarun.com/'.$image['image']?>">
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
                <p class="tm-action tm-clear">
                    <span id="J_EditItem"><a href="#" target="_blank">举报</a></span>
                    <a id="J_IShare" class="iShare tm-event" href="#"><i>&#xe607;</i>分享</a>
                    <a id="J_AddFavorite" href="javascript:;" data-aldurl="#" class="favorite"><i>&#xe609;</i><span>收藏商品</span></a>
                    <span id="J_CollectCount">（779人气）</span>
                </p>
            </div>
        </div>

    </div>
</div>
    <?php $this->beginBlock('JS_END') ?>
    //销售属性集
    var keys = eval(<?=json_encode($data['data']['sku_keys'])?>);
    var data = eval(<?=json_encode($data['data']['sku_data'])?>);
    //保存最后的组合结果信息
    var SKUResult = {};
    //获得对象的key
    function getObjKeys(obj) {
        if (obj !== Object(obj)) throw new TypeError('Invalid object');
        var keys = [];
        for (var key in obj)
            if (Object.prototype.hasOwnProperty.call(obj, key))
                keys[keys.length] = key;
        return keys;
    }

    //把组合的key放入结果集SKUResult
    function add2SKUResult(combArrItem, sku) {
        var key = combArrItem.join(";");
        if(SKUResult[key]) {//SKU信息key属性·
        SKUResult[key].count += sku.count;
        SKUResult[key].prices.push(sku.price);
        SKUResult[key].saleprices.push(sku.sale_price);
    } else {
            SKUResult[key] = {
                count : sku.count,
                prices : [sku.price],
                saleprices:[sku.sale_price]
            };
        }
    }
    //初始化得到结果集
    function initSKU() {
        var i, j, skuKeys = getObjKeys(data);
        for(i = 0; i < skuKeys.length; i++) {
            var skuKey = skuKeys[i];//一条SKU信息key
            var sku = data[skuKey];	//一条SKU信息value
            var skuKeyAttrs = skuKey.split(";"); //SKU信息key属性值数组
            skuKeyAttrs.sort(function(value1, value2) {
                return parseInt(value1) - parseInt(value2);
            });

            //对每个SKU信息key属性值进行拆分组合
            var combArr = combInArray(skuKeyAttrs);
            for(j = 0; j < combArr.length; j++) {
                add2SKUResult(combArr[j], sku);
            }

            //结果集接放入SKUResult
            SKUResult[skuKeyAttrs.join(";")] = {
                count:sku.count,
                prices:[sku.price],
                saleprices:[sku.sale_price]
            }
        }
    }

    /**
     * 从数组中生成指定长度的组合
     * 方法: 先生成[0,1...]形式的数组, 然后根据0,1从原数组取元素，得到组合数组
     */
    function combInArray(aData) {
        if(!aData || !aData.length) {
            return [];
        }

        var len = aData.length;
        var aResult = [];

        for(var n = 1; n < len; n++) {
            var aaFlags = getCombFlags(len, n);
            while(aaFlags.length) {
                var aFlag = aaFlags.shift();
                var aComb = [];
                for(var i = 0; i < len; i++) {
                    aFlag[i] && aComb.push(aData[i]);
                }
                aResult.push(aComb);
            }
        }

        return aResult;
    }


    /**
     * 得到从 m 元素中取 n 元素的所有组合
     * 结果为[0,1...]形式的数组, 1表示选中，0表示不选
     */
    function getCombFlags(m, n) {
        if(!n || n < 1) {
            return [];
        }

        var aResult = [];
        var aFlag = [];
        var bNext = true;
        var i, j, iCnt1;

        for (i = 0; i < m; i++) {
            aFlag[i] = i < n ? 1 : 0;
        }

        aResult.push(aFlag.concat());

        while (bNext) {
            iCnt1 = 0;
            for (i = 0; i < m - 1; i++) {
                if (aFlag[i] == 1 && aFlag[i+1] == 0) {
                    for(j = 0; j < i; j++) {
                        aFlag[j] = j < iCnt1 ? 1 : 0;
                    }
                    aFlag[i] = 0;
                    aFlag[i+1] = 1;
                    var aTmp = aFlag.concat();
                    aResult.push(aTmp);
                    if(aTmp.slice(-n).join("").indexOf('0') == -1) {
                        bNext = false;
                    }
                    break;
                }
                aFlag[i] == 1 && iCnt1++;
            }
        }
        return aResult;
    }


    function fmoney(s, n)
    {
    n = n > 0 && n <= 20 ? n : 2;
    s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
    var l = s.split(".")[0].split("").reverse(),
    r = s.split(".")[1];
    t = "";
    for(i = 0; i < l.length; i ++ )
    {
    t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "" : "");
    }
    return t.split("").reverse().join("") + "." + r;
    }

    //初始化用户选择事件
    $(function() {
        initSKU();
        $('.J_TSaleProp .sku').each(function() {
            var self = $(this);
            var attr_id = self.attr('attr_id');
            if(!SKUResult[attr_id]) {
                self.addClass("tb-out-of-stock");
            }else{
             if(SKUResult[attr_id] && SKUResult[attr_id].count==0 ){
                self.addClass("tb-out-of-stock");
            }
            }
        }).not('.tb-out-of-stock').click(function() {
            var self = $(this);
            //选中自己，兄弟节点取消选中
            self.toggleClass('tb-selected').siblings().removeClass('tb-selected');
            //已经选择的节点
            var selectedObjs = $('.J_TSaleProp .tb-selected');
            if(selectedObjs.length) {
                //获得组合key价格
                var selectedIds = [];
                selectedObjs.each(function() {
                    selectedIds.push($(this).attr('attr_id'));
                });
                selectedIds.sort(function(value1, value2) {
                    return parseInt(value1) - parseInt(value2);
                });
                var len = selectedIds.length;
                if(SKUResult[selectedIds.join(';')]){
                var prices = SKUResult[selectedIds.join(';')].saleprices;
                var maxPrice = Math.max.apply(Math, prices);
                var minPrice = Math.min.apply(Math, prices);
                var sale_price=maxPrice > minPrice ? minPrice + "-" + maxPrice : maxPrice;
                var prices = SKUResult[selectedIds.join(';')].prices;
                var maxPrice = Math.max.apply(Math, prices);
                var minPrice = Math.min.apply(Math, prices);
                var price=maxPrice > minPrice ? minPrice + "-" + maxPrice : maxPrice;
                $('#J_StrPriceModBox .tm-price').text(fmoney(sale_price,2));
                $('#J_PromoPrice .tm-price').text(fmoney(price,2));
                $('#J_EmStock').text('库存'+SKUResult[selectedIds.join(';')].count+'件');
                if(SKUResult[selectedIds.join(';')].count==0){
                $('#J_LinkBuy').addClass("noPost");
                 $('#J_LinkBasket').addClass("noPost");
                }else{
                $('#J_LinkBuy').removeClass("noPost");
                $('#J_LinkBasket').removeClass("noPost");
                }
                }
                //用已选中的节点验证待测试节点 underTestObjs
                $(".J_TSaleProp .sku").not(selectedObjs).not(self).each(function() {
                    var siblingsSelectedObj = $(this).siblings('.tb-selected');
                    var testAttrIds = [];//从选中节点中去掉选中的兄弟节点
                    if(siblingsSelectedObj.length) {
                        var siblingsSelectedObjId = siblingsSelectedObj.attr('attr_id');
                        for(var i = 0; i < len; i++) {
                            (selectedIds[i] != siblingsSelectedObjId) && testAttrIds.push(selectedIds[i]);
                        }
                    } else {
                        testAttrIds = selectedIds.concat();
                    }
                    testAttrIds = testAttrIds.concat($(this).attr('attr_id'));
                    testAttrIds.sort(function(value1, value2) {
                        return parseInt(value1) - parseInt(value2);
                    });
                    if(!SKUResult[testAttrIds.join(';')]) {
                        $(this).addClass("tb-out-of-stock").removeClass('tb-selected');
                    } else {
                        $(this).removeClass('tb-out-of-stock');
                    }
                });
            } else {
                //设置默认价格
               // $('.tm-price').text('--');
                //设置属性状态
                $('.sku').each(function() {
                    SKUResult[$(this).attr('attr_id')] ? $(this).removeClass('tb-out-of-stock') : $(this).addClass("tb-out-of-stock").removeClass('tb-selected');
                })
            }
        });
    });

    <?php $this->endBlock() ?>
<?php
    yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
    ?>

<?php }else{ ?>
<div id="detail">
    <img src="http://192.168.1.224:8084/images/product_base.jpg">
    </div>
<?php }?>