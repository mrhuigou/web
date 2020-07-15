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
$this->title =$model->name.'-首页';
?>
    <header class="whitebg bs-bottom lh44">
        <a class="pa-lt mt5 ml10" href="<?=Url::to(['/shop/index','shop_code'=>$model->store_code])?>"><img src="<?=Image::resize($model->logo,50,50)?>" width="30" height="30" class="mt2"></a>
        <div class="pr mr10 ml50">
            <form action="/shop/search" method="get" id="search_form">
            <input class="input minput w f14" type="text" value="" autocomplete="off" tabindex="1" placeholder="搜索关键字" name="keyword">
            <input class="input sinput w" type="hidden" value="<?=$model->store_code?>" name="shop_code" autocomplete="off">
            <a href="javascript:void(0)" class="search-btn green fb pa-rt r5 iconfont">&#xe601; </a>
        </div>
    </header>
<section class="veiwport mt0 pl5 pr5">
<?php if($model->store_type!=='FLAGSHIP'){ ?>
    <div class="clearfix whitebg p5  mb5">
        <a class="db pw25 fl" href="<?=Url::to(['/shop/index','shop_code'=>$model->store_code])?>">
            <img class="db w fl" alt="<?=Html::encode($model->name)?>" src="<?=Image::resize($model->logo,200,200)?>">
        </a>
        <div class="pw70 fr">
            <p><a class="db f14 mt10 mb5 fb" href="<?=Url::to(['/shop/index','shop_code'=>$model->store_code])?>"><?=Html::encode($model->name)?></a></p>
            <i class="iconfont green">&#xe671;</i>
            <div class="clearfix w">
            <span class="fr db btn-collect green tc" data-type="store" data-type-id="<?=$model->store_id?>">
                <?php if($model->myCollectStatus){?>
                    <i class="iconfont f14">&#xe62d;</i><br>已收藏
                <?php }else{ ?>
                    <i class="iconfont f14">&#xe64b;</i><br>收藏
                <?php }?>
            </span>
<!--            <p class="fr green tc mr5">-->
<!--                <span>--><?//=count($model->collect)?><!--</span> <br>-->
<!--                粉丝数-->
<!--            </p>-->
            </div>
        </div>
    </div>
    <div class="whitebg lh44 pr tc gray9">
        <div class="mr50 row bdr f14 gray6">
            <a class="col-3 simsun red bdr" href="<?=Url::to(['/shop/index','shop_code'=>$model->store_code])?>">首页</a>
            <a class="col-3 simsun bdr" href="<?=Url::to(['/shop/search','shop_code'=>$model->store_code])?>">最新商品</a>
            <a class="col-3 simsun" href="<?=Url::to(['/cart/index','shop_code'=>$model->store_code])?>">购物车</a>
        </div>
        <a class="pa-rt iconfont w50" href="<?=Url::to(['/shop/category','shop_code'=>$model->store_code])?>">&#xe600;</a>
    </div>
<?php } ?>
    <?php if($model->h5Theme && $model->h5Theme->info){?>
    <?php foreach($model->h5Theme->info as $row){ ?>
        <?php if($row->theme_column_type=='ADS'){
                if(!$row->info){
                    continue;
                }
                ?>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach($row->info as $value){ ?>
            <div class="swiper-slide">
                <a href="<?=$value->url?>">
                    <img data-original="<?=Image::resize($value->image,640,266)?>" width="100%" class="w lazy db">
                </a>
            </div>
             <?php } ?>
        </div>
    </div>
       <?php } ?>
        <?php if($row->theme_column_type=='BLOCK_IMG'){
            if(!$row->info){
                continue;
            }
            ?>
            <div class="clearfix">
            <!--标题-->
            <div class="tit1  p5 mt5">
                <h2><?=$row->name?></h2>
            </div>
                <div class="row">
            <?php foreach($row->info as $value){ ?>
                    <a href="<?=$value->url?$value->url:'javascript:;'?>">
                <div class="col-3 pr5 pt5">
                    <img class="w lazy db" alt="<?=$value->title?>" data-original="<?=Image::resize($value->image,204,204)?>">
                </div>
                    </a>
            <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php if($row->theme_column_type=='BLOCK_AD'){
                if(!$row->info){
                    continue;
                }
                ?>
                <div class="clearfix">
                    <div class="row pt5 pb5">
                        <?php foreach($row->info as $value){ ?>
                            <a href="<?=$value->url?$value->url:'javascript:;'?>">
                            <div class="w ">
                                <img class="w lazy db" alt="<?=$value->title?>" data-original="<?=Image::resize($value->image,640,166)?>">
                            </div>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

        <?php if($row->theme_column_type=='PRODUCT'){
            if(!$row->info){
                continue;
            }
            ?>
                <div class="clearfix">
                    <!--标题-->
                    <div class="tit1  p5 mt5">
                        <h2><?=$row->name?></h2>
                    </div>
                    <ul class="row mt2">
            <?php foreach($row->info as $value){ ?>
                        <li class="col-2 p3">
                            <div class="whitebg pb10">
                                <a href="<?=Url::to(['/product/index','product_base_id'=>$value->product->product_base_id],true)?>">
                                    <img data-original="<?=Image::resize($value->product->productBase->image,300,300)?>"  class="w lazy db">
                                </a>
                                <p class="mt5 mxh35"><?=$value->product->description->name?> <span class="red"><?=$value->product->description->meta_keyword?></span></p>
                                <div class="pl5 pr5 clearfix">
                                    <span class="red f14 mt2 fl">￥<?=$value->product->productBase->price?></span>
                                    <?php if($value->product->productBase->online_status){?>
                                        <?php if($value->product->stockCount>0){?>
                                            <a class="fr db br5 p5 f20 iconfont redbg white" href="javascript:;" onclick="AddCart(<?=$value->product->product_base_id?>)">&#xe614;</a>
                                        <?php }else{ ?>
                                            <span class="fr gray ">卖光了</span>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <span class="fr gray ">已下架</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </li>
            <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if($row->theme_column_type=='HTML'){
                if(!$row->info){
                    continue;
                }
                ?>
                <?php foreach($row->info as $value){ ?>
                <div class="clearfix">
                    <div class="tit1 p5 mt5">
                        <h2><?=$value->title?></h2>
                    </div>
                    <div class="w bd p10 whitebg con-detail">
                        <?=\common\component\Helper\Helper::ClearImgWHB(Html::decode($value->contents))?>
                    </div>
                </div>
                <?php } ?>
            <?php } ?>
<?php } ?>
<?php } ?>
    <?php if($products=$model->getTopProducts(10)->all()){ ?>
        <div class="tit1 p5 mt5">
            <h2>全部商品</h2>
        </div>
        <?php foreach($products as $product){ ?>
        <div class="whitebg pt10 pl5 mt5">
            <a href="<?=Url::to(['/product/index','product_base_id'=>$product->product_base_id],true)?>">
            <h2 class="f14 gray6 mb10"><?=Html::encode($product->description->name)?></h2>
            <div class="row">
                <?php if($product->imagelist){ ?>
                <?php foreach(array_slice($product->imagelist,0,3) as $key=>$image){ ?>
                <div class="col-3 pr5">
                   <img class="w db" alt="<?=$product->description->name?>" src="<?=Image::resize($image,190,190)?>">
                </div>
                <?php } ?>
                <?php } ?>
            </div>
            </a>
            <p class="gray9 mt5 mb5 pt2"><?=Html::encode($product->description->meta_keyword)?></p>
            <div class="pb10 pl5 pr5 clearfix">
                <span class="red f14 mt2 fl">￥<?=$product->price?></span>
                <?php if($product->online_status){?>
                    <?php if($product->stockCount>0){?>
                        <a class="fr db br5 p5 f20 iconfont redbg white" href="javascript:;" onclick="AddCart(<?=$product->product_base_id?>)">&#xe614;</a>
                    <?php }else{ ?>
                        <span class="fr gray ">卖光了</span>
                    <?php } ?>
                <?php }else{ ?>
                    <span class="fr gray ">已下架</span>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <div class="w tc mt10 "><a class=" appbtn pr10 pl10 w " href="<?=Url::to(['/shop/search','shop_code'=>$model->store_code])?>">查看更多</a></div>
    <?php } ?>
</section>

<?=\h5\widgets\StoreMainMenu::widget(['code'=>$model->store_code])?>


<?php
$this->beginBlock('JS_IMAGE')
?>
$(".btn-collect").on('click',function(){
var obj=$(this);
$.post('<?=Url::to('/shop/collect',true)?>',{'data-type-id':$(this).attr('data-type-id')},function(data){
var json=$.parseJSON(data);
    if(json.result==1){
     obj.html('<i class="iconfont f14 ">&#xe62d;</i><br>已收藏');
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