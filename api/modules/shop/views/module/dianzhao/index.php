<?php $data=isset($data['display']['shop_data'])?$data['display']['shop_data']:[]; ?>
<div class="J_TModule" data-title="店铺招牌" data-spm-max-idx="12">
<div class="skin-box tb-module tshop-pbsm tshop-pbsm-shop-custom-banner" style="position: relative;">
    <s class="skin-box-tp"><b></b></s>
    <div class="skin-box-bd">
        <?php if(isset($data['dz_style']) && $data['dz_style']) { ?>
         <style> .tshop-pbsm-shop-custom-banner .banner-box {
                 background : url(<?php echo $data['shop_image']?>) repeat 0 0 !important;
                 height : 120px !important;
            }
            .tshop-pbsm-shop-custom-banner {
                height: 120px !important;
            }
         </style>
        <?php }else{ ?>
        <style>.grid-m .tshop-pbsm-shop-custom-banner .banner-box {
                background: none repeat scroll 0 0 transparent !important;
            }
            .tshop-pbsm-shop-custom-banner .banner-box {
                height: 120px !important;
            }
            .tshop-pbsm-shop-custom-banner {
                height: 120px !important;
            }</style>
        <?php }?>
        <div>
            <div class="banner-box">
                <?php if(isset($data['dz_style']) && $data['dz_style']==0) {
                 echo $data['content'];
                 }?>
            </div>
            <?php if(isset($data['dz_style']) && $data['dz_style']) { ?>
            <h2 class="title border-radius"><?php echo isset($data['shop_name'])?$data['shop_name']:'店铺名称';?></h2>
            <?php }?>
        </div>
    </div>
    <s class="skin-box-bt"><b></b></s>
    </div>
</div>