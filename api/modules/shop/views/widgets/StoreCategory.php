<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/19
 * Time: 14:00
 */
?>
<div class="tshop-pbsm-shop-nav-ch">
    <div class="skin-box-bd" style="width: 0px; height: 0px;">
        <div class="popup-content all-cats-popup">
            <div class="popup-inner">
                <ul class="J_TAllCatsTree cats-tree">
                    <li class="cat fst-cat">
                        <h4 class="cat-hd fst-cat-hd ">
                            <i class="cat-icon fst-cat-icon  active-trigger"></i>
                            <a class="cat-name fst-cat-name" href="<?=\yii\helpers\Url::to(['/shop/search','shop_code'=>$store_code],true)?>">全部商品</a>
                        </h4>
                    </li>
                    <?php foreach($data as $value){ ?>
                        <li class="cat fst-cat">
                            <h4 class="cat-hd fst-cat-hd ">
                                <i class="cat-icon fst-cat-icon"></i>
                                <a class="cat-name fst-cat-name" href="<?=\yii\helpers\Url::to(['/shop/category','shop_code'=>$value['store_code'],'cat_id'=>$value['category_store_code']],true)?>"><?=$value['name']?></a>
                            </h4>
                            <?php if(isset($value['children']) && $value['children']){ ?>
                                <div class="snd-pop">
                                    <div class="snd-pop-inner">
                                        <ul class="fst-cat-bd">
                                            <?php foreach($value['children'] as $v){ ?>
                                                <li class="cat snd-cat">
                                                    <h4 class="cat-hd snd-cat-hd">
                                                        <i class="cat-icon snd-cat-icon"></i>
                                                        <a href="<?=\yii\helpers\Url::to(['/shop/category',['shop_code'=>$v['store_code'],'cat_id'=>$v['category_store_code']]],true)?>" class="by-label by-sale snd-cat-name" rel="nofollow"><?=$v['name']?></a>
                                                    </h4>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                        </li>
                    <?php } ?>

                </ul>
            </div>
        </div>
        <div class="popup-content">
            <ul class="menu-popup-cats">
                <li class="sub-cat"><a href="" class="cat-name" rel="nofollow">短款羽绒服</a></li>
                <li class="sub-cat"><a href="" class="cat-name" rel="nofollow">中长款羽绒服</a></li>
            </ul>
        </div>
    </div>
</div>