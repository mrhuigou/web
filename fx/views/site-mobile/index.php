<?php
$this->title = "每日惠购";
\h5\assets\AppAsset::register($this);
?>
<header class="header">
	<a href="<?php echo \yii\helpers\Url::to('/site/index') ?>" class="header-left">
		<img src="/assets/images/logo2.png" width="110" class="mt5">
	</a>
	<!--20150609-->
	<div class="header-search clearfix pr">
		<form action="<?php echo \yii\helpers\Url::to(['/search/index'])?>" method="get" id="search_form">
			<input class="input-text s w fx-convert-tri" type="text" name="keyword"
			       value="<?= Yii::$app->request->get('keyword') ?>" autocomplete="off" tabindex="1">
			<a href="javascript:void(0)" class="search-btn iconfont">&#xe601;</a>
		</form>
	</div>
	<!--消息-->
	<a class="pa-rt tc w50" href="/message/index">
		<i class="iconfont green">&#xe670;</i>
		<?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->messageCount > 0) { ?>
			<em class="info-point info-point-red pa-rt"><?= Yii::$app->user->isGuest ? 0 : Yii::$app->user->identity->messageCount ?></em>
		<?php } ?>
	</a>
</header>
<?php if ($data) {
	foreach ($data as $key => $v) {
		$$key = $v; //赋值
	}
} ?>
<section class="veiwport" style="max-width: inherit;">
	<?php if ($silde) { ?>
		<div class="swiper-container" id="swiper-container_banner">
			<div class="swiper-wrapper">
				<?php foreach ($silde as $value) { ?>
					<div class="swiper-slide" >
						<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
							<img
								src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 266) ?>"
								title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" style="width: 32rem;height: 13.3rem;" class="lazy">
						</a>
					</div>
				<?php } ?>
			</div>
			<!-- Add Pagination -->
			<div class="swiper-pagination swiper-pagination-white"></div>
		</div>
	<?php } ?>
	<?php if ($zeroF_AD) { ?>
		<div class="pt10"></div>
		<div class="swiper-container" id="swiper-container_ad">
			<div class="swiper-wrapper">
				<?php foreach ($zeroF_AD as $value) { ?>
					<div class="swiper-slide">
						<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="m5">
							<img
								src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
								title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
								style="width: 10rem;height: 10rem;" class="lazy">
						</a>
					</div>
				<?php } ?>
			</div>
			<div class="swiper-button-next swiper-button-white"></div>
			<div class="swiper-button-prev swiper-button-white"></div>
		</div>
		<div class="pb10"></div>
	<?php } ?>

	<?php if($second_new){?>
	<div class="flex-col flex-center flex-middle">
		<?php foreach($second_new as $value){?>
			<a class="flex-item-6 p2" href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
				<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, $value->width, $value->height, 9) ?>" class="db w h lazy">
			</a>
		<?php }?>
	</div>
	<?php }?>
	<?= h5\widgets\News::widget() ?>
	<div class="pt10 pb5 f14 fb pl5 dn">特色栏目</div>
	<div class="tit-1">
		<h2>特色栏目</h2>
	</div>
    <div class="flex-col flex-center">
	    <div class="flex-item-4 ">
		    <?php if ($firstF_SECKILL) { ?>
			    <?php foreach (array_slice($firstF_SECKILL,0,1) as $key => $value) { ?>
					    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" >
						    <img
							    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, $value->width, $value->height, 9) ?>"
							    title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" class="db w h lazy">
					    </a>
			    <?php } ?>
		    <?php } ?>
	    </div>
	    <div class="flex-item-8 ">
		    <div class="flex-item-12 ">
			    <?php if ($firstF_FREEDLY) { ?>
				    <?php foreach (array_slice($firstF_FREEDLY,0,1) as $key => $value) { ?>
						    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db w pb5 pl5">
							    <img
								    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, $value->width, $value->height, 9) ?>"
								    title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" class="db w lazy">
						    </a>
				    <?php } ?>
			    <?php } ?>
		    </div>
		    <?php if ($firstF_ACTION) { ?>
			    <?php foreach (array_slice($firstF_ACTION,0,2) as $key => $value) { ?>
					    <div class="flex-item-6 ">
					    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db w pl5">
						    <img
							    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, $value->width, $value->height, 9) ?>"
							    title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" class="db w  lazy">
					    </a>
					    </div>
			    <?php } ?>
		    <?php } ?>
	    </div>
    </div>
	<div class="flex-col flex-center ml5 ">
		<?php if ($firstF_PROMOTION) { ?>
			<?php foreach (array_slice($firstF_PROMOTION,0,2) as $key => $value) { ?>
					<div class="flex-item-6 ">
					<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db w pt5 pr5 ">
						<img
							src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, $value->width, $value->height, 9) ?>"
							title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" class="db w h lazy">
					</a>
					</div>
			<?php } ?>
		<?php } ?>
	</div>
	<!----1F end--->
	<!----2F--->
	<div class="pt10 pb5 f14 fb pl5 dn">主题生活</div>
	<div class="tit-1">
		<h2>主题生活</h2>
	</div>
	<div class="flex-col flex-center">
		<?php if ($secondF_FRESHDRINK) { ?>
			<?php foreach ($secondF_FRESHDRINK as $key => $value) { ?>
				<div class="flex-item-6 ">
					<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db bdr">
						<img
							src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, $value->width, $value->height, 9) ?>"
							title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"  class=" db w lazy">
					</a>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<div class="flex-col flex-center">
		<?php if ($secondF_LIVE) { ?>
			<?php foreach ($secondF_LIVE as $key => $value) { ?>
				<div class="flex-item-4 ">
					<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db bdt bdr">
						<img
							src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
							title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"  class="db w lazy">
					</a>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<div id="brand">
	<div class="pt10 pb5 f14 fb pl5 dn">品牌专区</div>
	<div class="tit-1">
		<h2>品牌专区</h2>
	</div>
	<div class="flex-col flex-center">
		<?php if ($thirdF_BRAND) { ?>
			<?php foreach ($thirdF_BRAND as $key => $value) { ?>
				<div class="flex-item-12">
					<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
						<img
							src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 200) ?>"
							title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" style="width: 32rem;height: 10rem;" class="db  lazy">
					</a>
			<?php } ?>
		<?php } ?>
	</div>
	</div>
	<div class="menu-tab">
		<ul id="nav" class="filter blueFilter five clearfix mt10 top-list-tab tabs ">
			<li class="cur"><a href="#shengxian">生鲜</a></li>
			<li><a href="#jiushui">酒水</a></li>
			<li><a href="#baihuo">休食</a></li>
			<li><a href="#shipin">粮油</a></li>
			<li><a href="#xihua">洗化</a></li>
            <li><a href="#muying">母婴</a></li>
		</ul>
	</div>
	<div class="panels">
		<div class="panel" id="shengxian">
			<!--产品组-->
			<?php if ($fourthF_PRODUCT_ONE) { ?>
				<ul class="pro-list23">
					<?php foreach ($fourthF_PRODUCT_ONE as $key => $value) { ?>
						<?php if ($value['advertise_media_type'] == 'PACK') { ?>
							<li class="clearfix">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="	w-per30 fl">
									<img class="w fl mr15 lazy"
									     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->product ? $value->product->image : $value->source_url, 200, 200) ?>"
									     title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" style="width: 10rem;height: 10rem;">
								</a>

								<div class="w-per65 fr">
									<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"
									   class="db pt5 pb5 f14"><?= $value->product ? $value->product->description->name : $value->title ?></a>

									<p class="red pb5"><?= $value->product ? $value->product->description->meta_description : '' ?></p>

									<div class="pt5">
										<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="Last fr">
											<span>马上抢</span>
										</a>

										<p class="mt2">
											<span
												class="red fb f14">￥<?= $value->product ? $value->product->getPrice() : '' ?></span><br>
											<span class="gray9">参考价：<i
													class="del">￥<?= $value->product ? $value->product->price : '' ?></i></span>
										</p>
									</div>
								</div>
							</li>
						<?php } elseif ($value['advertise_media_type'] == 'IMAGE') { ?>
							<li class="clearfix pb5">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db   lazy" style="width: 32rem;height: 10rem;">
								</a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>

			<!--品牌两个广告-->
			<div class="row">

				<?php if ($fourthF_BRAND_ONE) { ?>
					<?php foreach ($fourthF_BRAND_ONE as $key => $value) { ?>
						<?php if ($key < 3) { ?>
							<div class="col-3">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db p2">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db  lazy" style="width: 10rem;height: 10rem;">
								</a>
							</div>
						<?php } ?>
					<?php } ?>
				<?php } ?>


			</div>

			<!--品牌logo组-->
			<?php if ($fourthF_PLOGO_ONE) { ?>
				<ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
				    style="margin-top:0.4rem;">
					<?php foreach ($fourthF_PLOGO_ONE as $key => $value) { ?>
						<?php if ($key < 5) { ?>
							<li>
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"><img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="lazy" ></a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
		<div class="panel" id="jiushui">
			<!--产品组-->
			<?php if ($fourthF_PRODUCT_TWO) { ?>
				<ul class="pro-list23">
					<?php foreach ($fourthF_PRODUCT_TWO as $key => $value) { ?>
						<?php if ($value['advertise_media_type'] == 'PACK') { ?>
							<li class="clearfix">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="	w-per30 fl">
									<img class="w fl mr15 lazy"
									     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->product ? $value->product->image : $value->source_url, 200, 200) ?>"
									     title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" style="width: 10rem;height: 10rem;">
								</a>

								<div class="w-per65 fr">
									<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"
									   class="db pt5 pb5 f14"><?= $value->product ? $value->product->description->name : $value->title ?></a>

									<p class="red pb5"><?= $value->product ? $value->product->description->meta_description : '' ?></p>

									<div class="pt5">
										<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="Last fr">

											<span>马上抢</span>
										</a>

										<p class="mt2">
											<span
												class="red fb f14">￥<?= $value->product ? $value->product->getPrice() : '' ?></span><br>
											<span class="gray9">参考价：<i
													class="del">￥<?= $value->product ? $value->product->price : '' ?></i></span>
										</p>
									</div>
								</div>
							</li>
						<?php } elseif ($value['advertise_media_type'] == 'IMAGE') { ?>
							<li class="clearfix pb5">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db  lazy" style="width: 32rem;height: 10rem;">
								</a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>

			<!--品牌3个广告-->
			<div class="row">
				<?php if ($fourthF_BRAND_TWO) { ?>
					<?php foreach ($fourthF_BRAND_TWO as $key => $value) { ?>
						<?php if ($key < 3) { ?>
							<div class="col-3">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db p2">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db  lazy" style="width: 10rem;height: 10rem;">
								</a>
							</div>
						<?php } ?>
					<?php } ?>
				<?php } ?>


			</div>

			<!--品牌logo组-->
			<?php if ($fourthF_PLOGO_TWO) { ?>
				<ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
				    style="margin-top:0.4rem;">
					<?php foreach ($fourthF_PLOGO_TWO as $key => $value) { ?>
						<?php if ($key < 5) { ?>
							<li>
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"><img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="lazy" ></a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>


		<div class="panel" id="baihuo">
			<!--产品组-->
			<?php if ($fourthF_PRODUCT_FIVE) { ?>
				<ul class="pro-list23">
					<?php foreach ($fourthF_PRODUCT_FIVE as $key => $value) { ?>
						<?php if ($value['advertise_media_type'] == 'PACK') { ?>
							<li class="clearfix">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="	w-per30 fl">
									<img class="w fl mr15 lazy"
									     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->product ? $value->product->image : $value->source_url, 200, 200) ?>"
									     title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" style="width: 10rem;height: 10rem;">
								</a>

								<div class="w-per65 fr">
									<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"
									   class="db pt5 pb5 f14"><?= $value->product ? $value->product->description->name : $value->title ?></a>

									<p class="red pb5"><?= $value->product ? $value->product->description->meta_description : "" ?></p>

									<div class="pt5">
										<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="Last fr">

											<span>马上抢</span>
										</a>

										<p class="mt2">
											<span
												class="red fb f14">￥<?= $value->product ? $value->product->getPrice() : '' ?></span><br>
											<span class="gray9">参考价：<i
													class="del">￥<?= $value->product ? $value->product->price : '' ?></i></span>
										</p>
									</div>
								</div>
							</li>
						<?php } elseif ($value['advertise_media_type'] == 'IMAGE') { ?>
							<li class="clearfix pb5">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db  lazy" style="width: 32rem;height: 10rem;">
								</a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>

			<!--品牌3个广告-->
			<div class="row">

				<?php if ($fourthF_BRAND_FIVE) { ?>
					<?php foreach ($fourthF_BRAND_FIVE as $key => $value) { ?>
						<?php if ($key < 3) { ?>
							<div class="col-3">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db p2">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db  lazy" style="width: 10rem;height: 10rem;">
								</a>
							</div>
						<?php } ?>
					<?php } ?>
				<?php } ?>


			</div>

			<!--品牌logo组-->
			<?php if ($fourthF_PLOGO_FIVE) { ?>
				<ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
				    style="margin-top:0.4rem;">
					<?php foreach ($fourthF_PLOGO_FIVE as $key => $value) { ?>
						<?php if ($key < 5) { ?>
							<li>
								<a href="<?= \yii\helpers\Url::to(\yii\helpers\Url::to($value->link_url, true), true) ?>"><img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="lazy" ></a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
		<div class="panel" id="shipin">
			<!--产品组-->
			<?php if ($fourthF_PRODUCT_THREE) { ?>
				<ul class="pro-list23">
					<?php foreach ($fourthF_PRODUCT_THREE as $key => $value) { ?>
						<?php if ($value['advertise_media_type'] == 'PACK') { ?>
							<li class="clearfix">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="	w-per30 fl">
									<img class="w fl mr15 lazy"
									     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->product ? $value->product->image : $value->source_url, 200, 200) ?>"
									     title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" style="width: 10rem;height: 10rem;">
								</a>

								<div class="w-per65 fr">
									<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"
									   class="db pt5 pb5 f14"><?= $value->product ? $value->product->description->name : $value->title ?></a>

									<p class="red pb5"><?= $value->product ? $value->product->description->meta_description : '' ?></p>

									<div class="pt5">
										<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="Last fr">

											<span>马上抢</span>
										</a>

										<p class="mt2">
											<span
												class="red fb f14">￥<?= $value->product ? $value->product->getPrice() : '' ?></span><br>
											<span class="gray9">参考价：<i
													class="del">￥<?= $value->product ? $value->product->price : '' ?></i></span>
										</p>
									</div>
								</div>
							</li>
						<?php } elseif ($value['advertise_media_type'] == 'IMAGE') { ?>
							<li class="clearfix pb5">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db  lazy" style="width: 32rem;height: 10rem;">
								</a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>

			<!--品牌3个广告-->
			<div class="row">

				<?php if ($fourthF_BRAND_THREE) { ?>
					<?php foreach ($fourthF_BRAND_THREE as $key => $value) { ?>
						<?php if ($key < 3) { ?>
							<div class="col-3">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db p2">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db  lazy" style="width: 10rem;height: 10rem;">
								</a>
							</div>
						<?php } ?>
					<?php } ?>
				<?php } ?>


			</div>
			<!--品牌logo组-->
			<?php if ($fourthF_PLOGO_THREE) { ?>
				<ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
				    style="margin-top:0.4rem;">
					<?php foreach ($fourthF_PLOGO_THREE as $key => $value) { ?>
						<?php if ($key < 5) { ?>
							<li>
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"><img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="lazy" ></a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
		<div class="panel" id="xihua">
			<!--产品组-->
			<?php if ($fourthF_PRODUCT_FOUR) { ?>
				<ul class="pro-list23">
					<?php foreach ($fourthF_PRODUCT_FOUR as $key => $value) { ?>
						<?php if ($value['advertise_media_type'] == 'PACK') { ?>
							<li class="clearfix">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="	w-per30 fl">
									<img class="w fl mr15 lazy"
									     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->product ? $value->product->image : $value->source_url, 200, 200) ?>"
									     title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" style="width: 10rem;height: 10rem;">
								</a>

								<div class="w-per65 fr">
									<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"
									   class="db pt5 pb5 f14"><?= $value->product ? $value->product->description->name : $value->title ?></a>

									<p class="red pb5"><?= $value->product ? $value->product->description->meta_description : '' ?></p>

									<div class="pt5">
										<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="Last fr">

											<span>马上抢</span>
										</a>

										<p class="mt2">
											<span
												class="red fb f14">￥<?= $value->product ? $value->product->getPrice() : '' ?></span><br>
											<span class="gray9">参考价：<i
													class="del">￥<?= $value->product ? $value->product->price : '' ?></i></span>
										</p>
									</div>
								</div>
							</li>
						<?php } elseif ($value['advertise_media_type'] == 'IMAGE') { ?>
							<li class="clearfix pb5">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db  lazy" style="width: 32rem;height: 10rem;">
								</a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>

			<!--品牌3个广告-->
			<div class="row">
				<?php if ($fourthF_BRAND_FOUR) { ?>
					<?php foreach ($fourthF_BRAND_FOUR as $key => $value) { ?>
						<?php if ($key < 3) { ?>
							<div class="col-3">
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db p2">
									<img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="db  lazy" style="width: 10rem;height: 10rem;">
								</a>
							</div>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>

			<!--品牌logo组-->
			<?php if ($fourthF_PLOGO_FOUR) { ?>
				<ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
				    style="margin-top:0.4rem;">
					<?php foreach ($fourthF_PLOGO_FOUR as $key => $value) { ?>
						<?php if ($key < 5) { ?>
							<li>
								<a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"><img
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
										title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
										class="lazy" ></a>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
        <div class="panel" id="muying">
            <!--产品组-->
            <?php if ($fourthF_PRODUCT_SIX) { ?>
                <ul class="pro-list23">
                    <?php foreach ($fourthF_PRODUCT_SIX as $key => $value) { ?>
                        <?php if ($value['advertise_media_type'] == 'PACK') { ?>
                            <li class="clearfix">
                                <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="	w-per30 fl">
                                    <img class="w fl mr15 lazy"
                                         src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->product ? $value->product->image : $value->source_url, 200, 200) ?>"
                                         title="<?php echo $value->title; ?>" alt="<?= $value->title ?>" style="width: 10rem;height: 10rem;">
                                </a>

                                <div class="w-per65 fr">
                                    <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"
                                       class="db pt5 pb5 f14"><?= $value->product ? $value->product->description->name : $value->title ?></a>

                                    <p class="red pb5"><?= $value->product ? $value->product->description->meta_description : '' ?></p>

                                    <div class="pt5">
                                        <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="Last fr">
                                            <span>马上抢</span>
                                        </a>

                                        <p class="mt2">
											<span
                                                    class="red fb f14">￥<?= $value->product ? $value->product->getPrice() : '' ?></span><br>
                                            <span class="gray9">参考价：<i
                                                        class="del">￥<?= $value->product ? $value->product->price : '' ?></i></span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        <?php } elseif ($value['advertise_media_type'] == 'IMAGE') { ?>
                            <li class="clearfix pb5">
                                <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>">
                                    <img
                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 640, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="db   lazy" style="width: 32rem;height: 10rem;">
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            <?php } ?>

            <!--品牌两个广告-->
            <div class="row">

                <?php if ($fourthF_BRAND_SIX) { ?>
                    <?php foreach ($fourthF_BRAND_SIX as $key => $value) { ?>
                        <?php if ($key < 3) { ?>
                            <div class="col-3">
                                <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>" class="db p2">
                                    <img
                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="db  lazy" style="width: 10rem;height: 10rem;">
                                </a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>


            </div>

            <!--品牌logo组-->
            <?php if ($fourthF_PLOGO_SIX) { ?>
                <ul class="pro-list shadowbd-box bdradius-box five clearfix whitebg bdb pt10"
                    style="margin-top:0.4rem;">
                    <?php foreach ($fourthF_PLOGO_SIX as $key => $value) { ?>
                        <?php if ($key < 5) { ?>
                            <li>
                                <a href="<?= \yii\helpers\Url::to($value->link_url, true) ?>"><img
                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"  data-original="<?= \common\component\image\Image::resize($value->source_url, 200, 200) ?>"
                                            title="<?php echo $value->title; ?>" alt="<?= $value->title ?>"
                                            class="lazy" ></a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
	</div>
</section>

<!--浮动购物车-->
<?= h5\widgets\MainMenu::widget(); ?>
<?php $this->beginBlock("JS") ?>
var swiper_ad = new Swiper('#swiper-container_ad', {
nextButton: '.swiper-button-next',
prevButton: '.swiper-button-prev',
paginationClickable: true,
slidesPerView: 3,
slidesPerGroup : 3,
autoplay: 4000,
autoplayDisableOnInteraction: false
});
$("header").scrollFix({zIndex : 999});
$(".menu-tab").scrollFix({startTop:"#nav", zIndex : 1000,distanceTop:$("header").outerHeight()});
$('#nav').onePageNav({currentClass:"cur",scrollThreshold: 0.5,easing: 'swing',top:-80});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS'], \yii\web\View::POS_LOAD);
?>



