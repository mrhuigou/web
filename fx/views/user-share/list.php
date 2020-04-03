<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/24
 * Time: 18:42
 */
$this->title="推荐分享";
$type=Yii::$app->request->get('type','page');
?>
<header class="bg-wh lh44 bs-b pr zi9">
	<div class="flex-col tc">
		<a href="javascript:history.back();" class="flex-item-2 green">
			<i class="iconfont arr-l vm-2"></i>
		</a>
		<div class="flex-item-8">
            <a class="btn btn-s <?=$type=='page'?'btn-org':'btn-bd-gray'?>" href="<?=\yii\helpers\Url::to(['user-share/list','type'=>'page'])?>">活动推广</a>
			<a class="btn btn-s <?=$type=='product'?'btn-org':'btn-bd-gray'?>" href="<?=\yii\helpers\Url::to(['user-share/list','type'=>'product'])?>">商品推广</a>
            <a class="btn btn-s btn-bd-gray" href="<?=\yii\helpers\Url::to(['/coupon/coupon-rules','id'=>4])?>">新人福利</a>
		</div>
		<div class="flex-item-2"></div>
	</div>
</header>
<div class="content">
	<!-- 列表 -->

	<?php if($ad_data){?>
		<?php if($type=='product'){?>
	<?php foreach ($ad_data as $value){?>
		<div class="flex-col bg-wh p10 mb10">
			<div class="flex-item-3">
				<a href="<?=\yii\helpers\Url::to(['product/index','store_code'=>$value->product->store_code,'product_code'=>$value->product->product_code,'share_tip'=>1])?>">
					<img src="<?=\common\component\image\Image::resize($value->product->image,200,200)?>" alt="<?=$value->product->description->name?>" class="w">
				</a>
			</div>
			<div class="flex-item-9 pl10">
				<a href="<?=\yii\helpers\Url::to(['product/index','store_code'=>$value->product->store_code,'product_code'=>$value->product->product_code,'share_tip'=>1])?>" class="db row-two-max"><?=$value->product->description->name?></a>
				<p class="red mt5 mb5 f16">￥<?=$value->product->getPrice()?></p>
				<p class="gray6 f12 none">预计收益<?=$value->product->getCommission(Yii::$app->user->getId(),$value->product->getPrice())?>元</p>
                <a href="<?=\yii\helpers\Url::to(['product/index','store_code'=>$value->product->store_code,'product_code'=>$value->product->product_code,'share_tip'=>1])?>"><i class="iconfont fr red f35" style="margin-top: -50px;">&#xe6a5;</i></a>
			</div>
		</div>
	<?php }?>
			<?php }else{?>
			<?php foreach ($ad_data as $value){?>
				<div class="bg-wh p10 mb5">
					<div class="mb10">
                        <?php
                            if(strpos($value->link_url,'?')){
                                $link_url = $value->link_url."&share_tip=1";
                            }else{
                                $link_url = $value->link_url."?share_tip=1";
                            }

                        ?>
						<a class="green fr" href="<?= \yii\helpers\Url::to($link_url, true) ?>">分享</a>
						<span class="f12"><?= $value->title ?></span>
					</div>
					<a href="<?= \yii\helpers\Url::to($link_url, true) ?>" class="db mb5">
						<img src="<?= \common\component\image\Image::resize($value->source_url,640,266) ?>" class="w">
					</a>
				</div>
			<?php }?>
			<?php }?>
	<?php }else{ ?>
		<figure class="info-tips bg-wh gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前任何推广信息</figcaption></figure>
	<?php }?>
</div>
<!--浮动购物车-->
<?=fx\widgets\MainMenu::widget();?>
