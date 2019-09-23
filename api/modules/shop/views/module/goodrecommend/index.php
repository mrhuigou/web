<?php
use yii\helpers\Html;
?>
<div class="J_TModule" >
<div class="skin-box tb-module tshop-pbsm tshop-pbsm-shop-item-recommend">
        <s class="skin-box-tp"><b></b></s>
            <div class="skin-box-hd">
                <i class="hd-icon"></i>
                <?php if(isset($data['display']['title']) && $data['display']['title'] ){ ?>
                <h3><span><?php echo $data['display']['title']; ?></span></h3>
                <?php }else{ ?>
                <h3><span>宝贝推荐</span></h3>
                <?php }?>
                <div class="skin-box-act">
                <a href="#" class="more" target="_blank" rel="nofollow">更多</a>
                </div>
            </div>
        <div class="skin-box-bd">
            <?php if(isset($data['display']['display_style']) && $data['display']['display_style'] ){ ?>
              <div class="item<?php echo $data['display']['display_style']; ?>line1">
              <?php }else{ ?>
              <div class="item3line1">
              <?php }?>
              <?php if(isset($data['content']['product_list']) && $data['content']['product_list'] ) { ?>
               <?php foreach($data['content']['product_list'] as $product ){ ?>
              <dl class="item" >
                  <dt class="photo">
                      <a href="<?=$product['href']?>" target="_blank">
                          <img src="<?=$product['product_image']?>" alt="<?=$product['product_name']?>">
                      </a>
                  </dt>
                  <dd class="detail">
                      <a class="item-name" href="<?=$product['href']?>" target="_blank"><?php echo $product['product_name'];?></a>
                      <div class="attribute">
                          <div class="cprice-area"><span class="symbol">¥</span><span class="c-price"><?php echo $product['product_vip_price'];?></span></div>
                      </div>
                  </dd>
              </dl>
              <?php } ?>
              <?php } ?>
          </div>
        </div>
    <s class="skin-box-bt"><b></b></s>
    </div>
</div>