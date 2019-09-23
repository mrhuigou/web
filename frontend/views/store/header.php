<link href="/assets/stylesheets/global.css" rel="stylesheet">
<link href="/assets/stylesheets/layout.css" rel="stylesheet">
<link href="/assets/stylesheets/modules.css" rel="stylesheet">
<link href="/assets/stylesheets/page.css" rel="stylesheet">
<link href="/assets/stylesheets/detail.css" rel="stylesheet">
<link href="/assets/stylesheets/suggest.css" rel="stylesheet">
<link href="/page/shop-temp/greenbox/css/view.css" rel="stylesheet">
<link href="/page/shop-temp/greenbox/css/foucsbox.css" rel="stylesheet">
<link href="/page/shop-temp/greenbox/css/layout.css" rel="stylesheet">
<link href="/assets/stylesheets/icommon.css" rel="stylesheet">
<div class="<?php echo $theme_color_code?>">
		<!--头部-->
		<div>
			<p class="w1100 bc tr pt5 pb5"></p>
        </div>

<!--店铺banner背景设置-->
<div class="shops_banner">
    <div class="w1100 bc clearfix">
        <a href="<?php echo yii\helpers\Url::to(['store/index','shop_code'=>$model->store_code]);?>">
            <img src="<?php if($model->logo){ echo \common\component\image\Image::resize($model->logo,98,106);}?>" alt="<?php echo $model->name;?>" width="98" height="106" class="fl mr15" /></a>
        <div class="w600 fl pt30">
            <span class="f30 shops_tit"><?php echo $model->name;?></span>
            <span class="pt5 db shops_tit_des"><?php echo $model->storeDescription->meta_description;?></span>
        </div>
        <div class="w300 pt20 fr clearfix">
            <div class="clearfix">
                <a href="javascript:void(0)" onclick="addtofavstore()" class="shop_c ml15 fr"></a>
                <ul class="shop_quality fr clearfix mt5">
                    <li class="q"></li>
                    <li class="z"></li>
                    <li class="l"></li>
                    <li class="r"></li>
                </ul>
            </div>
            <div class="clearfix mt10 fr">
                <span class="fl dib shops_rate" style="margin-top:3px;">店铺评分：<span class="shops_percent pr10"><?php echo number_format((( $review_score['rating'] +  $review_score['service']+ $review_score['delivery'])/3) *10,2) ?>%</span> 店铺分享：</span>
                <!-- Baidu Button BEGIN -->
                <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
                    <a class="bds_qzone" title="分享到QQ空间" href="#"></a>
                    <a class="bds_tsina" title="分享到新浪微博" href="#"></a>
                    <a class="bds_renren" title="分享到人人网" href="#"></a>
                    <span class="bds_more"></span>
                </div>
                <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=6815188" src="http://bdimg.share.baidu.com/static/js/bds_s_v2.js?cdnversion=387704"></script>
                <!-- Baidu Button END -->
            </div>
        </div>
    </div>
</div>
    <?php $this->beginBlock('JS_END') ?>
    function addtofavstore(){
        var csrf = '<?php echo Yii::$app->request->csrfToken;?>';
        var store_id = <?php echo $model->store_id;?>;
        addToWishList(0,store_id,'store',csrf);
    }

    <?php $this->endBlock() ?>
    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
    ?>
<!--nav-->

<div class="shop_navbox">
    <div class="w1100 bc shop_nav clearfix">
        <a href="<?php echo yii\helpers\Url::to(['store/index','shop_code'=>$model->store_code]);?>" class="<?php if(isset($column_id) && !empty($column_id)){ echo '';}else{ echo "current";}?> ">店铺首页</a>

        <?php  $storeThemeColumn = $model->theme->storeThemeColumn;

        ?>
<?php foreach($storeThemeColumn as $info){
    if(strtoupper($info->theme_column_type) == 'MENU'){
        if(!empty($info->store_theme_column_info)){
            foreach($info->store_theme_column_info as $k => $column_info){
                if(isset($column_info->url) && !empty($column_info->url)){

                    $link = $column_info->url;
                }else{
                    $link = yii\helpers\Url::to(['store/information','column_id'=>$column_info->store_theme_column_id,'shop_code'=>$model->store_code]);
                }
                ?>

                <a class="<?php if(isset($column_id) && !empty($column_id) && $column_id == $menu['store_theme_column_id'] ){ echo 'current';}?>"
                   href="<?php echo $link;?>" target="_blank"><?php echo $info->name;?></a>
        <?php
            }
        }else{
            $link = "javascript:void(0)";
        }

        $theme_menu_info[] = $info;

    }
}?>


    </div>
</div>