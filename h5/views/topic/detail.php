<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use \common\component\image\Image;

/* @var $this yii\web\View */
$this->title = $model->name;
?>
	<header class="fx-top bs-bottom whitebg lh44">
		<div class="flex-col tc">
			<a class="flex-item-2" href="javascript:history.back();">
				<i class="aui-icon aui-icon-left green f28"></i>
			</a>

			<div class="flex-item-8 f16">
				<?= Html::encode($this->title) ?>
			</div>
			<a class="flex-item-2" href="/">
				<i class="aui-icon aui-icon-home green f28"></i>
			</a>
		</div>
	</header>
	<section class="veiwport">
		<div class="mb50" style="padding-top: 45px;">
			<?php if ($model->image_url) { ?>
				<div class="flex-col flex-middle mb5 pr">
					<img src="<?= \common\component\image\Image::resize($model->image_url, 640, 230,9) ?>" alt="" class="db w">
					<div class="pa-lb t10 r10 flex-col flex-middle flex-right none">
						<div class="flex-item-5 opc-f p5">
							<h2 class="fb f16 tc"><?= $model->name ?></h2>
							<p class="p10 f14 tc lh200"><?= $model->description ?></p>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if($details){ ?>
				<?php foreach($details as $detail){?>
					<div class="whitebg mb10">
						<h2 class="f16 pl10 lh200 "><?= $detail->product->description->name ?></h2>
						<p class="red pl10 lh150">
                            <!--促销方案详情-->
                            <?php if($detail->product->Promotions){?>
                                <?php foreach ($detail->product->Promotions as $promotion) { ?>
                                    <?php if ($promotion->promotion_detail_title) { ?>
                                        <?= Html::encode($promotion->promotion_detail_title) ?></p>
                                    <?php }?>
                                <?php }?>
                            <?php }?>

                            <?= $detail->product->description->meta_keyword ?></p>
						<?php if($detail->product->productBase->bedisplaylife){?>
							<p class="pl10 mt5" >
								<span class="p2 bd-green green lh150 f12">保质期 :<?=$detail->product->productBase->life?></span>
								<?php if($detail->product->productDate){?><span class="p2 greenbg white lh150 f12">生产日期：<?=$detail->product->productDate?></span><?php }?>
							</p>
						<?php } ?>
						<?php if($images=$detail->product->images){ ?>
						<a class="flex-col" href="<?=Url::to(['/product/index','product_code'=>$detail->product->product_code,'shop_code'=>$detail->product->store_code])?>">
							<?php foreach( array_slice($images,0,3) as $value){?>
							<div class="flex-item-4 p5">
									<img src="<?= \common\component\image\Image::resize($value->image, 203, 203) ?>" alt="" class="db w">
							</div>
							<?php } ?>
						</a>
						<?php } ?>

						<div class="p5 clearfix">
							<p class="fl pt5">
								<span class="f14 red fb mr5">￥<?= $detail->getCurPrice() ?></span>
								<span class="gray9 del">￥<?=$detail->product->price ?></span>
							</p>
								<?php if($detail->product->beintoinv){?>
									<?php if($detail->product->stockCount>0){ ?>
										<a class="fr btn mbtn redbtn" href="javascript:;" onclick="AddCart(<?=$detail->product->product_base_id?>)">立即购买</a>
									<?php }else{ ?>
										<span class="fr btn mbtn graybtn ">已售罄</span>
									<?php } ?>
								<?php }else{ ?>
									<span class="fr btn mbtn graybtn ">已下架</span>
								<?php } ?>
						</div>
					</div>
				<?php } ?>
			<?php }?>

		</div>
	</section>
<?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $data = [
        'title' =>$model->name,
        'desc' => '口袋超市，物美价廉，当天订单，当天送。',
        'link' => Yii::$app->request->getAbsoluteUrl(),
        'image' => Yii::$app->request->getHostInfo().'/assets/images/zhqd/logo_300x300.jpeg'
    ];
}else{
    $data = [
        'title' =>$model->name,
        'desc' => '家润每日惠购，物美价廉，当天订单，当天送。',
        'link' => Yii::$app->request->getAbsoluteUrl(),
        'image' => Yii::$app->request->getHostInfo().'/assets/images/mrhuigou_logo.jpg'
    ];
}?>
<?php h5\widgets\Tools\Share::widget(['data'=>$data]);?>
<?= h5\widgets\MainMenu::widget(); ?>