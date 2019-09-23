<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/4
 * Time: 11:59
 */
use \yii\helpers\Html;
?>
	<div class="clearfix">
		<a href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$model->productbase->product_base_code,'shop_code'=>$model->productbase->store_code])?>">
			<div class="p5">
				<h2 class="f14 gray6 "><?=Html::encode($model->productbase->description->name)?></h2>
				<p class="green"><?=Html::encode($model->productbase->description->meta_keyword)?></p>
			</div>
			<div class="row pl5">
				<?php if($model->productbase->imagelist){ ?>
					<?php foreach(array_slice($model->productbase->imagelist,0,3) as $key=>$image){ ?>
						<div class="col-3 pr5">
							<img data-original="<?=\common\component\image\Image::resize($image,190,190)?>" alt="<?=$model->productbase->description->name?>" class="lazy db w fl mr15" >
						</div>
					<?php } ?>
				<?php }else{ ?>
					<div class="col-3 pr5">
						<img data-original="<?=\common\component\image\Image::resize($model->productbase->defaultImage,190,190)?>" alt="<?=$model->productbase->description->name?>" class="lazy db w fl mr15" >
					</div>
				<?php } ?>
			</div>
		</a>
		<div class="p5 clearfix">
			<p class="fl pt5">
				<span class="red f14  ">￥<?=$model->productbase->price?></span><span class="del pl10 gray9">￥<?=$model->productbase->sale_price?></span>
			</p>
			<a class="fr ml5 btn mbtn bluebtn " href="<?=\yii\helpers\Url::to(['/collect/cancel','id'=>$model->customer_collect_id])?>">取消收藏</a>
			<?php if($model->productbase->online_status){?>
				<?php if($model->productbase->stockCount>0){?>
					<a class="fr btn mbtn redbtn" href="<?=\yii\helpers\Url::to(['/product/index',"product_code"=>$model->productbase->product_base_code,'shop_code'=>$model->productbase->store_code])?>">购买</a>
				<?php }else{ ?>
					<span class="fr btn mbtn graybtn ">已售罄</span>
				<?php } ?>
			<?php }else{ ?>
				<span class="fr btn mbtn graybtn ">已下架</span>
			<?php } ?>
		</div>
	</div>
