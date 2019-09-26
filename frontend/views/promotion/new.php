<?php
$this->title ='新品尝鲜 – 上架新品 新品预售 – 家润新品 - 家润慧生活（mrhuigou.com）-青岛首选综合性同城网购-发现达人体验-分享同城生活';
?>
<div class="w1100 bc pb30 clearfix pr50 pl50">
<div class="newpro">
    <!--新品头部开始-->
    <div class="newpro_title">
        <div class="newpro_title_img">
            <img src="/assets/images/fgy/newpro_title.jpg" alt="新品尝鲜" />
        </div>
    </div>
    <!--新品头部结束-->
<input name="_csrf" type="hidden" value="<?php echo Yii::$app->request->csrfToken;?>" />
    <!--新品1介绍开始-->
    <?php if(isset($data['products']) && $data['products']){?>
        <?php foreach ($data['products'] as $product){?>
            <div class="newpro_details shadowBox pt20 pb20">
                <div class="newproducts">
                    <ul class="newproducts_u">
                        <li class="newproducts_u_image"  id="product_<?php echo $product['product_id'];?>">
                            <a name="<?php echo $product['product_code'];?>" href="<?php echo $product['product_link'];?>"><img border="0" src="<?php echo $product['pmt_image'];?>"  alt="<?php echo $product['name'];?>" /></a>
                            <!-- <dl class="newpro_price">
              <dt class="newpro_p_1_font dt_1"><b class="newpro_p_1_b_font">¥</b><?php echo $product['vip_price'];?></dt>
              <dt class="del p_price_font dt_2">会员价:<?php echo $product['price'];?></dt>
              <dt class="p_price_font dt_2 dt_3"><?php echo $product['rebate'];?>折</dt>
            </dl> -->
                        </li>
                    </ul>
                    <ul class="newproducts_u_2">
                        <li class="pro_name" style="height:auto;"><a href="<?php echo $product['product_link'];?>" target="_blank"><?php echo $product['name'];?></a></li>
                        <li class="pro_hot"><?php echo $product['meta_description'];?></li>

                    </ul>
                    <div style="line-height:50px;" class="pt15 pb15">
                        <span style="font-size:50px;" class="red vm dib pl20">¥<?php echo $product['vip_price'];?></span>
                        <span class="f14 gray6 vm"><i class="del pl15 pr5">会员价:¥<?php echo $product['price'];?></i> <?php echo $product['rebate'];?>折</span>
                    </div>
                    <p class="pt10">


                        <span class="f14 pl10"> <?php if(!empty($product['limitquantity'])){ echo '(限量'. $product['limitquantity'] .'份)';}?>  </span>
                        <!--<span class="attSale ml10 <?php if(isset($_COOKIE["panicFav_".Yii::$app->user->getId().'-'.$product['product_id']])){ echo ' cur'; } ?>" onclick="addFavourite(<?php echo $product['product_id'];?>)">关注</span> <span class="f14">（<i class="red fb"><?php echo $product['favnums'];?></i>人已关注）</span>
                    -->
                    </p>
                    <ul class="user_buy">
                        <?php if($product['is_merge']){?>
                            <?php if($product['quantity'] > 0){?>
                                <li class="user_buy_s1"><span class="user_btn" onclick="buynow(<?php echo $product['product_id'];?>)">立即尝鲜</span></li>
                                <li class="user_buy_s2">
                                    <span class="user_btn" onclick="addToCart('<?php echo $product['product_id'];?>','1','listpro')">加入购物车</span>
                                </li>
                            <?php }else{ ?>
                                <li class="user_buy_s1 f_l"><span class="user_btn">已抢完</span></li>
                            <?php }}else{?>
                            <?php if($product['quantity'] > 0){?>
                                <li class="user_buy_s1"><span class="user_btn" onclick="buynow(<?php echo $product['product_id'];?>)">立即尝鲜</span></li>
                                <li class="user_no_buy_s2">
                                    <span class="user_btn">加入购物车</span>
                                </li>
                            <?php }else{ ?>
                                <li class="user_buy_s1 f_l"><span class="user_btn">已抢完</span></li>
                            <?php }}?>
                    </ul>
                </div>
            </div>
        <?php }?>
    <?php }else{?>
        还没有新品尝鲜哎！
    <?php }?>

</div>
<div class="flash_sale_bg wid1100"></div>
</div>