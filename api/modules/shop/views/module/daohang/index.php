<?php
use yii\helpers\Url;
?>
<div class="J_TModule"  data-title="导航">
<div class="skin-box tb-module tshop-pbsm tshop-pbsm-shop-nav-ch" style="display: block; visibility: visible;">
    <s class="skin-box-tp"><b></b></s>
    <div class="skin-box-bd">
            <div class="all-cats popup-container">
                <div class="all-cats-trigger popup-trigger">
                    <a class="link " href="<?=Url::to(['/shop/category','shop_code'=>Yii::$app->request->get('shop_code')],true)?>">
                     <span class="title">本店所有商品</span>
                        <i class="popup-icon"></i>
                    </a>
                </div>
            </div>
        <?php if(isset($data['content']['daohang']) && $data['content']['daohang'] ) { ?>
                <ul class="menu-list">
                    <?php foreach( $data['content']['daohang'] as $value ) { ?>
                    <li class="menu" ><a class="link" href="<?php echo $value['href'];?>" rel="nofollow"><span class="title"><?php echo $value['title'];?></span></a>
                    </li>
                    <?php } ?>
                </ul>
        <?php } ?>
    </div>
    <s class="skin-box-bt"><b></b></s>
    </div>
</div>




