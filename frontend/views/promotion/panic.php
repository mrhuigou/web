<?php
$this->title ='闪购 – 特价秒杀 - 家润闪购 - 家润慧生活（365jiarun.com）-青岛首选综合性同城网购-发现达人体验-分享同城生活';
?>
<div class="wrap">

    <div class="flash_sale_bg wid1100 pr50 pl50">
        <!--flash sale top-->
        <div class="flash_sale_bg_top">
            <?php if( strtoupper(Yii::$app->request->get("subject")) == 'PANIC'){?>
                <div class="flash_sale_bg_top_bigI">
                    <img src="/assets/images/fgy/flash_sale_title.jpg" alt="闪购" />
                </div>
            <?php }?>


        </div>
        <input name="_csrf" type="hidden" value="<?php echo Yii::$app->request->csrfToken;?>" />
        <?php if(isset($data['products']) && $data['products']){ ?>
            <?php foreach ($data['products'] as $product){?>

                <!--flash sale middle-->
                <div class="flash_sale_bg_mid clearfix wid1100 mt20" id="product_fav_<?php echo $product['product_id'];?>">
                    <div class="flash_sale_bg_mid_l">
                        <div class="flash_sale_bg_mid_l_img"  id="product_<?php echo $product['product_id'];?>">

                            <a name="<?php echo $product['product_code'];?>" target="_blank" href="<?php echo $product['product_link'];?>"><img border="0" alt="<?php echo $product['name'];?>" src="<?php echo $product['pmt_image'];?>"/></a>
                        </div>
                        <div class="flash_sale_bg_mid_l_0">



                            <?php $this->beginBlock('JS_END') ?>
                                $(function(){
                                    var endTime = new Date($('#flip-counter_<?php echo $product['product_id'];?>').data('date'));

                                    var finished = false;
                                    var countdown = $.Countdown({
                                        endTime:endTime,
                                        afterFinish: function() {
                                            finished = true;
                                        }
                                    });

                                    var myCounter = new flipCounter('flip-counter_<?php echo $product['product_id'];?>', {
                                        dateFormat:true,
                                        value:countdown.toString(),
                                        auto:false,
                                        tFH:20,
                                        bFH:20,
                                        bOffset:200,
                                        fW:30,
                                        speed:40
                                    });

                                    $('#flip-counter_<?php echo $product['product_id'];?>_d1').hide();
                                    $('#flip-counter_<?php echo $product['product_id'];?>_d0').hide();

                                    var intervalId = setInterval(function() {

                                        if (finished) {
                                            clearInterval(intervalId);
                                            return;
                                        }

                                        countdown.update();
                                        myCounter.setValue(countdown.toString());

                                    }, 1000);

                                });
                            <?php $this->endBlock() ?>
                            <?php
                            \yii\web\YiiAsset::register($this);
                            $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
                            ?>
                        </div>
                    </div>
                    <div class="flash_sale_bg_mid_r">
                        <div class="r_flash_1">
                            <!--<b class="flash_newfont">闪购</b>-->
                            <span class="pdt_name" style="height:auto;"><a target="_blank" href="<?php echo $product['product_link'];?>"><?php echo $product['name'];?></a></span>
                        </div>
                        <div class="r_flash_2">
                            <span class="pdt_hot"><?php echo $product['meta_description'];?></span>
                        </div>
                        <div style="line-height:50px;" class="pt15 pb15">
                            <span style="font-size:50px;" class="red vm">¥<?php echo $product['vip_price'];?></span>
                            <span class="f14 gray6 vm"><i class="del pl15 pr5">会员价:¥<?php echo $product['price'];?></i> <?php echo $product['rebate'];?>折</span>
                        </div>
                        <!--倒计时翻页开始-->

                        <div class="flash_sale_bg_mid_l_time_1">
                            <div id="flip-counter_<?php echo $product['product_id'];?>" class="flip-counter" data-date="<?php echo date('M d,Y H:i:s',strtotime($product['data_end']));?>"></div>
                            <div class="time">
                                <span class="cutdown">倒计时</span>
                                <span class="day">天</span>
                                <span class="hour">时</span>
                                <span class="minute">分</span>
                            </div>
                        </div>
                        <!--倒计时翻页结束-->
                        <p class="pt10">
                            <span class="f14"> <?php if(!empty($product['limitquantity'])){ echo '(限量'. $product['limitquantity'] .'份)';}?>  </span>
                            <!--
                            <span class="attSale <?php if(isset($_COOKIE["panicFav_".Yii::$app->user->getId().'-'.$product['product_id']])){ echo ' cur'; } ?>" onclick="addFavourite(<?php echo $product['product_id'];?>)">关注</span> <span class="f14">（<i class="red fb"><?php echo $product['favnums'];?></i>人已关注）</span>
                        -->
                        </p>

                        <div class="r_flash_5">
                            <ul style="float:left;">
                                <?php if($product['is_merge']){?>
                                    <?php if($product['quantity'] > 0){?>
                                        <li class="user_buy_s1_red f_l"><span class="user_btn" onclick="buynow(<?php echo $product['product_id'];?>)">立即购买</span></li>
                                        <li class="user_buy_s2 red f_l">
                                            <span class="user_btn" onclick="addToCart('<?php echo $product['product_id'];?>','1','listpro')">加入购物车</span>
                                        </li>
                                    <?php }else{?>
                                        <li class="user_buy_s1_red f_l"><span class="user_btn">已抢完</span></li>
                                    <?php }?>

                                <?php }else{?>
                                    <?php if($product['quantity'] > 0){?>
                                        <li class="user_buy_s1_red f_l"><span class="user_btn" onclick="buynow(<?php echo $product['product_id'];?>)">立即购买</span></li>
                                        <li class="user_buy_s2 f_l" style="border:1px solid rgb(204, 204, 204)">
                                            <span class="user_btn grayBtn" >加入购物车</span>
                                        </li>
                                    <?php }else{?>
                                        <li class="user_buy_s1_red f_l"><span class="user_btn" >已抢完</span></li>
                                    <?php }?>

                                <?php }?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>

            <?php }?>
        <?php }?>
    </div>
</div>

<div class="clearfix"></div>


<script type="text/javascript">
    function addFavourite(product_id){
        var product_id = product_id;
        $.ajax({
            url: '/index.php?route=product/promotion_detail/addFavouriteAjax',
            type:'POST',
            dataType: 'json',
            data: 'p_id='+product_id,
            success: function(html){
                if(html == 'already'){
                    alert("您已经参与过了~");
                    $("#product_fav_"+product_id).find(".zhan").addClass('cur');
                    //window.location.href = "<?php //echo $this->url->link('account/login','','SSL');?>";
                }else if(html == 'false'){
                    alert("提交出了问题，请重试");
                }else{
                    window.location.reload();
                    $("#product_fav_"+product_id).find(".zhan").addClass('cur');
                    $("#product_fav_"+product_id).find(".heart_num").html(html);
                    //$("#product_fav_"+product_id).find(".heart_num").html(html);

                }
            }

        });
    }
</script>
<?php ?>
