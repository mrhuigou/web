
<div id="header" style="margin-top:0!important;">
    <!--top-->
    <?php if ($this->beginCache('frontend-header-top')) { ?>
        <?=$this->renderDynamic('return \frontend\widgets\HeaderTop::widget();');?>
        <?php $this->endCache(); }?>
    <!--logo和search-->
    <div class="header-box w1100 bc">
        <!--logo 和搜索区-->
        <div class="clearfix pt15 pb5">
            <a href="<?=\yii\helpers\Url::to('/site/index')?>" class="fl mt5 ">
                <img src="/assets/images/v3_logo.png" alt="logo" width="201" height="46" class="db">
            </a>
            <div class="fl">
                <?=frontend\widgets\SearchBar::widget()?>
            </div>
            <a href="javascript:void(0)" class="fr">
                <img src="/assets/images/carton7.10.gif" alt="68元包邮" width="215" height="67" class="db">
            </a>
        </div>

        <div class="nav_bx bc clearfix">
            <div class="allcate fl">
                <h1><i class="iconfont"></i>所有商品分类 </h1>
                <div class="allcate-con " style="width: 210px;display: none;" >
                    <div class="category-menu">
                        <div class="category-nav">
                            <?php if(!empty($category_arrays)){?>
                                <ul class="menu-nav-container">

                                    <?php foreach($category_arrays as $sec_categorys){?>
                                        <li>
                                            <h3 class="clearfix">
                                                <p class="fl">
                                                    <a target="_blank" href="<?php echo \yii\helpers\Url::to(['product/category','cat_id'=>$sec_categorys->category_display_id])?>"><?php echo $sec_categorys->description->name;?></a>
                                                </p>
                                                <i class="fr iconfont"></i>
                                            </h3>
                                            <?php if(!empty($sec_categorys->child)){?>
                                            <div class="menu-content-panel clearfix" style="display: none;">
                                                <div class="menu-content-panel-l fl">
                                                    <?php foreach($sec_categorys->child as $sec_category){?>
                                                        <a href="<?php echo \yii\helpers\Url::to(['product/category','cat_id'=>$sec_category->category_display_id])?>"><h2><?php echo $sec_category->description->name;?></h2></a>
                                                        <p>
                                                            <?php if(!empty($sec_category->child)){?>
                                                                <?php foreach($sec_category->child as $third_category){?>
                                                                    <a href="<?php echo \yii\helpers\Url::to(['product/category','cat_id'=>$third_category->category_display_id])?>"><?php echo $third_category->description->name;?></a>
                                                                <?php }?>
                                                            <?php }?>
                                                        </p>
                                                    <?php }?>
                                                </div>
                                                <?php if($sec_categorys->brand){?>
                                                    <div class="menu-content-panel-r fr clearfix">
                                                        <?php foreach($sec_categorys->brand as $brand){ ?>
                                                            <a href="<?=\yii\helpers\Url::to(['/category/index','brand_id'=>$brand->brand_id])?>"><img width="95" height="45" class="db"  src="<?=\common\component\image\Image::resize($brand->image,95,45)?>"></a>
                                                        <?php } ?>
                                                    </div>
                                                <?php }?>
                                            </div>
                                            <?php }?>
                                        </li>
                                    <?php }?>
                                </ul>
                            <?php }?>

                        </div>

                    </div>
                </div>
            </div>
            <div class="w600 fl navbar clearfix">
                <a href="<?php echo \yii\helpers\Url::to(['club/try'])?>" class="cur">免费试</a>
                <a href="<?php echo \yii\helpers\Url::to(['club/default'])?>">生活圈</a>
                <a href="/promotion/NEW.html">新品来袭</a>
                <a href="/promotion/PANIC.html">闪购</a>
            </div>
        </div>

    </div>
</div>