<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/9/18
 * Time: 16:02
 */
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = $model->name;
?>
<section class="veiwport pb50" style="max-width: inherit;">
	<ul class="filter redFilter two f16 clearfix" style="border-bottom: 1px solid #ff463c;">
		<li class="<?=Yii::$app->request->get('status')?'':'cur'?>">
			<a href="<?=Url::to(['/topic/detail','code'=>$model->code])?>">今日抢购</a>
		</li>
		<li class="<?=Yii::$app->request->get('status')?'cur':''?>">
			<a href="<?=Url::to(['/topic/detail','code'=>$model->code,'status'=>1])?>">明日预告</a>
		</li>
	</ul>
	<?php if($details){ ?>
	<div class="flex-col">
		<div class="flex-item-7 p10">
			<h2 class="fb">限时抢购</h2>
			<p class="gray9 f12">每天0点更新</p>
		</div>
		<div class="flex-item-5 p10 tr">
			<p class="gray6">
				距结束倒计时</p>
			<p class="countdown">
				<span class="hour_show">00</span>:<span class="minute_show">00</span>:<span class="second_show">00</span>
			</p>
		</div>
	</div>
	<div class="whitebg">
	<?php foreach($details as $detail){?>
		<div class="clearfix p10 bdb">
			<a href="<?=Url::to(['/product/index','product_code'=>$detail->product->product_code,'shop_code'=>$detail->product->store_code])?>" class="db pw30 fl">
				<img data-original="<?= \common\component\image\Image::resize($detail->product->image, 180, 180) ?>" class="lazy db w fl mr15" >
			</a>
			<div class="pw66 fr">
				<a href="<?=Url::to(['/product/index','product_code'=>$detail->product->product_code,'shop_code'=>$detail->product->store_code])?>" class="db pb10"> <?= $detail->product->description->name ?></a>
				<p class="red mb5"><?= $detail->product->description->meta_keyword ?></p>
				<?php if($detail->product->productBase->bedisplaylife){?>
					<p class="mb5">
						<span class="p2 bd-green green lh150 f12">保质期 :<?=$detail->product->productBase->life?></span>
						<?php if($detail->product->productDate){?><span class="p2 greenbg white lh150 f12">生产日期：<?=$detail->product->productDate?></span><?php }?>
					</p>
				<?php } ?>
				<p class="fr">
					<?php if(strtotime($detail->begin_date)>time()){ ?>
						<button class="btn sbtn graybtn f12">即将开始...</button>
					<?php }else{ ?>
					<?php if($detail->product->beintoinv){?>
						<?php if($detail->product->stockCount>0){?>
							<a class="fr btn mbtn redbtn" href="javascript:;" onclick="AddCart(<?=$detail->product->product_base_id?>)">立即购买</a>
						<?php }else{ ?>
							<span class="fr btn mbtn graybtn ">已售罄</span>
						<?php } ?>
					<?php }else{ ?>
						<span class="fr btn mbtn graybtn ">已下架</span>
					<?php } ?>
					<?php } ?>
				</p>
				<p>
					<span class="f16 red fb">￥<?= $detail->getCurPrice() ?></span>
					<span class="del grayc f12">￥<?=$detail->product->price ?></span>
				</p>

			</div>
		</div>
	<?php } ?>
		</div>
	<?php } ?>

</section>
<?php $this->beginBlock("JS") ?>
	timer(<?=max(0,strtotime($model->date_end)-time())?>,$(".countdown"));
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS'], \yii\web\View::POS_READY);
?>
<?= h5\widgets\MainMenu::widget(); ?>