<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use \common\component\image\Image;
/* @var $this yii\web\View */
$this->title ='店铺促销';
?>
<header class="header w" id="header">
    <a class="pa-lt iconfont leftarr" href="javascript:history.back();" ></a>
    <h2><?= Html::encode($this->title) ?></h2>
    <a href="<?php echo \yii\helpers\Url::to(['/site/index'])?>" class="header-cate iconfont">&#xe602;</a>
</header>
<section class="veiwport">
    <?php if($model){ ?>
    <?php foreach($model as $value){ ?>
    <div class="clearfix">
    <div class="tit1 p5 mt5">
        <h2><?=$value->name?></h2>
    </div>
     <?php if($value->details){?>
            <?php foreach($value->details as $value){ ?>
                    <div class="pw50 fl p5">
                        <div class="whitebg">
                            <a href="<?=Url::to(['/product/index','product_base_id'=>$value->product->product_base_id],true)?>"><img src="<?=Image::resize($value->product->productBase->image,200,200)?>" alt="tu" class="db w"></a>
                            <div class="p5">
                                <p class="mxh20"><?=$value->product->description->name?></p>
                                <div class="pt5">
                                    <span class="red"><?=number_format($value->price,2,'.','')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
           <?php } ?>
     <?php } ?>
    </div>
  <?php } ?>
 <?php }else{ ?>
        <figure class="info-tips">
            <i class="iconfont font-404 ">&#xe616;</i>
            <figcaption class="mt10">当前还没有任何促销，稍后在来吧！</figcaption>
        </figure>
 <?php } ?>

</section>