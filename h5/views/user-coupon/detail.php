<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/9
 * Time: 17:26
 */
$this->title ='优惠券详情';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  pb50">
	<div class="whitebg flex-col aui-border mt5 mb5 ">
		<div class="flex-item-3 p5">
				<img src="<?=\common\component\image\Image::resize($coupon->coupon->image_url,200,200)?>" alt="" class="db w">
		</div>
		<div class="flex-item-6 flex-m p5 pt10 pb10">
			<h2 class="f16"><?=$coupon->coupon->name?></h2>
			<p class="gray9"><?=date('m/d',strtotime($coupon->start_time))?> ~  <?=date('m/d',strtotime($coupon->end_time))?></p>
			 <p class="red f18"><?php if ($coupon->coupon->type=='F'){ ?>
					 ￥<?=number_format($coupon->coupon->discount,2,'.','')?>
				 <?php }else{ ?>
					 <?=$coupon->coupon->getRealDiscount()?>折
				 <?php }?>
				 <?php if($coupon->is_use){ ?>
					 <span class="p5 f12 redbg white br5 ">已使用</span>
				 <?php }elseif(strtotime($coupon->end_time)<time()){?>
					 <span class="p5 f12 graybg white br5 ">过期</span>
				 <?php }?>
                </p>
			<p class="p10">

			</p>

		</div>
	</div>

	<div class="p5 graybg mt5 mb5">
		<?=$coupon->coupon->comment?$coupon->coupon->comment:$coupon->coupon->description?>
	</div>
	<div class="pb20">
	<?= \yii\widgets\ListView::widget([
		'layout' => "{items}\n{pager}",
		'dataProvider' => $dataProvider,
		'options' => ['class' => 'list-view row'],
		'itemOptions' => ['class' => 'item col-3 p5 '],
		'emptyText' => '<figure class="info-tips  gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有信息</figcaption></figure>',
		'itemView' => '_item_product_view',
		'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
			'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
			'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
			'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
		]
	]);
	?>
	</div>
</section>
<?= h5\widgets\MainMenu::widget(); ?>