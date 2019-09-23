<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/30
 * Time: 11:32
 */
use yii\helpers\Html;
use yii\helpers\Url;
use \common\component\image\Image;
/* @var $this yii\web\View */
$this->title =$store->name.'-店铺上新';
?>
<header class="header w" id="header">
    <div class="tit-left"><a class="iconfont leftarr" href="<?=Url::to(['site/index'])?>"></a></div>
    <div class="pr pl30 mr10">
        <form action="/shop/search" method="get" id="search_form">
            <input class="input sinput w" type="text" value="" autocomplete="off" tabindex="1" name="keyword">
            <input class="input sinput w" type="hidden" value="<?=$store->store_code?>" name="shop_code" autocomplete="off">
            <a href="javascript:void(0)" class="search-btn iconfont">&#xe601;</a>
        </form>
    </div>
</header>
<section class="veiwport mt0">
    <!--  店招    -->
    <div class="bg-banner s">
        <div class="pa-lb pl5 pb5">
            <a href="<?=Url::to(['/shop/index','shop_code'=>$store->store_code])?>" class="fl">
                <img src="<?=Image::resize($store->logo,50,50)?>" alt="<?=$store->name?>" class="logo" width="50" height="50">
            </a>
            <p class="fl ml5 white pt15"><?=$store->name?></p>
        </div>
        <div class="pa-rb pb5">
            <a class="fr db bn-collect " href="javascript:;" data-type="store" data-type-id="<?=$store->store_id?>">
                <?php if($store->myCollectStatus){?>
                    <i class="iconfont f14">&#xe62d;</i><br>已收藏
                <?php }else{ ?>
                    <i class="iconfont f14">&#xe64b;</i><br>收藏
                <?php }?>
            </a>
            <p class="fr white tc mr5">
                <span><?=count($store->collect)?></span> <br>
                粉丝数
            </p>
        </div>
    </div>
    <ul class="filter  redFilter four bdb clearfix">
        <li><a href="<?=Url::to(['/shop/index','shop_code'=>$store->store_code])?>">店铺首页</a></li>
        <li><a href="<?=Url::to(['/shop/search','shop_code'=>$store->store_code])?>">全部商品</a></li>
        <li><a href="<?=Url::to(['/shop/category','shop_code'=>$store->store_code])?>">商品分类</a></li>
        <li><a href="<?=Url::to(['/shop/hot','shop_code'=>$store->store_code])?>">店铺推荐</a></li>
    </ul>
    <div class="clearfix">
    <?= \yii\widgets\ListView::widget([
        'layout' => "{items}\n{pager}",
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'list-view clearfix'],
        'itemOptions' => ['class' => 'item  pw50 fl p5'],
        'emptyTextOptions' => ['class' => 'empty tc p10 '],
        'itemView' => '_item_view',
        'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
            'triggerTemplate'=>'<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">{text}</button></div>',
            'noneLeftTemplate'=>'<div class="ias-noneleft tc p10">{text}</div>',
        ]
    ]);
    ?>
    </div>

</section>
<?php
$this->beginBlock('JS_IMAGE')
?>
    $(".bn-collect").live('click',function(){
    var obj=$(this);
    $.post('<?=Url::to('/shop/collect',true)?>',{'data-type-id':$(this).attr('data-type-id')},function(data){
    var json=$.parseJSON(data);
    if(json.result==1){
    obj.html('<i class="iconfont f14">&#xe62d;</i><br>已收藏');
    }else{
    obj.html('<i class="iconfont f14">&#xe64b;</i><br>收藏');
    }
    });
    });
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_IMAGE'],\yii\web\View::POS_READY);
?>