<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/28
 * Time: 19:41
 */
$this->title="领红包";
?>
<style>
    body{background-color:#fcecd2;}
</style>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
	<section class="veiwport" style="max-width: inherit;">
		<!-- 红包 -->
		<img src="/assets/images/hongbao/hb5-bg.jpg" class="w">

		<div class="p10" style="background-color:#fcecd2;">
			<?php if($status){?>
				<p class="tc f16 red fb mb10"> 亲，你已经领过红包啦，看看其它分类吧！</p>
			<?php }else{?>
				<p class="tc f16 red fb mb10"> 亲，恭喜您获得一张现金红包！</p>
			<?php }?>
			<?php if($model){?>
			<div class="br5 mb10 activity-1-coupon">
				<div class="flex-col bd-d-b">
					<div class="flex-item-7">
						<h3 class="red pt5 mt1"><?=$model->coupon->name?></h3>
						<span class="f14"><?=$model->coupon->description?></span>
					</div>
					<div class="flex-item-5 red tr">
						<span class="f25">￥</span><span class="f25"><?=$model->coupon->realDiscountName?></span>
					</div>
				</div>
				<div class="f14 pt5">
					<span class="gray9">截止：<?=date('Y-m-d',strtotime($model->end_time))?> 有效</span>
				</div>
			</div>
			<?php }?>
			<a href="/user-coupon/index" class="btn btn-l btn-red w mt10 mb20">查看优惠券</a>
		</div>
		<?php if ($ad) { ?>
            <div class="row ">
				<?php foreach ($ad as $value) { ?>
                    <div class="col-2 p5 ">
                        <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_code'=>$value->product->product_code])?>" class="db w">
                            <div class="item ">
                                <div class="item-padding-none">
                                    <div class="item-inner">
                                        <div class="item-photo">
                                            <img src="<?= \common\component\image\Image::resize($value->product->image,200,200) ?>" alt="" class="db w" /> <!--已售罄-->
											<?php if(!$value->product->getStockCount()){?><i class="item-tip iconfont">&#xe696;</i><?php }?>
                                        </div>
                                        <div class="item-detail">
                                            <p class="row-two f12 mt2 mb2">
												<?=$value->product->description->name?>
                                            </p>
                                            <span class="red">￥<?=$value->product->getPrice()?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
				<?php } ?>
            </div>
		<?php } ?>
        <div class="fx-bottom">
		<img src="/assets/images/hongbao/hb5-img.jpg" class="w"></div>

	</section>
