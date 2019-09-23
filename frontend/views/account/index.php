<?php $this->title = '用户中心'; ?>
<div class="w990 bc pt20 clearfix simsun">
    	<div class="fl w100 mr10 menu-tree">
		<?=frontend\widgets\UserSiderbar::widget()?>
    	</div>
    	<div class="fl w630">
    		<div class="bd">
	    		<div class="graybg pt5 pb5 clearfix">
	    			<div class="info-base fl">
	    				<a href="#" class="info-avatar">
	    					<img src="<?= \common\component\image\Image::resize(Yii::$app->user->identity->photo,100,100);?>" alt="avatar" width="57" height="57">
	    				</a>
	    				<p class="mt5"><a href="#" class="tahoma"><?=$model->nickname;?></a></p>
	    				<span class="org">余额：<?=Yii::$app->user->getIdentity()->balance;?>元 </span><a href="<?=\yii\helpers\Url::to(['/account-recharge'],true) ?>" class="btn lh150 pr5 pl5 littleGreenBtn">充值</a><br>
	    				<span class="org">积分：<?=$model->points?$model->points:0;?></span>
	    			</div>	
	    			<ul class="info-stuff fr">
	    				<li><a href="<?=\yii\helpers\Url::to(['/address'],true) ?>">我的收货地址</a></li>
	    				<li><a href="<?=\yii\helpers\Url::to(['/coupon'],true) ?>">我的优惠信息</a></li>
	    				<li><a href="<?=\yii\helpers\Url::to(['/collect'],true) ?>">我的收藏</a></li>
	    			</ul>	
	    		</div>
	    		<div class="bdt order-status clearfix">
	    			<a href="<?=\yii\helpers\Url::to(['/order','order_status'=>1],true) ?>">
	    				<span>待付款</span>
	    			</a>
	    			<a href="<?=\yii\helpers\Url::to(['/order','order_status'=>3],true) ?>">
	    				<span>待发货</span>
	    			</a>
	    			<a href="<?=\yii\helpers\Url::to(['/order','order_status'=>9],true) ?>">
	    				<span>待收货</span>
	    			</a>
	    			<a href="<?=\yii\helpers\Url::to(['/order/review','review_type'=>1],true) ?>">
	    				<span>待评价</span>
	    			</a>
	    			<a href="<?=\yii\helpers\Url::to(['/order/return'],true) ?>">
	    				<span>退款</span>
	    			</a>
	    		</div>
	    	</div>

	    	<div class="bd mt10 mb50" style="background-color:#f8fffa;">
	    		<h2 class="tit-greenbg">我的浏览历史</h2>
	    		<ul class="guesslist clearfix pb10" id="myfav">
	    			
	    		</ul>
	    	</div>
    	</div>
    	<div class="fr w235">
			<div class="bd mt10 ">
				<h2 class="p10 f14 tit-greenbg">我的收藏</h2>
				<div class="clearfix pl5 pr5 pt10">
				<?php 
				if($footprint){
				foreach ($footprint as $value) { ?>
					<div class="pw33 fl tc mb5">
						<a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$value->product->store_code,'product_base_code'=>$value->product->product_base_code],true)?>"><img src="<?=\common\component\image\Image::resize($value->product->image,70,70) ?>" alt="tu" width="70" height="70" class="db bc"></a>
						<p class="mxh20 ml10 mr10 mt2 mb5"><?=$value->product->description->name;?></p>
					</div>
				<?php }
				}else{
					echo '暂无记录';
					} ?>
				</div>
			</div>
			
    	</div>
    </div>

	<?php $this->beginBlock("JS_Block")?>
		
		$.get('<?=\yii\helpers\Url::to(['/account/myfav'],true) ?>',function(data){
			$("#myfav").html(data);
		});

		$(".reload-btn").on('click',function(){
			$.get('<?=\yii\helpers\Url::to(['/account/myfav'],true) ?>',function(data){
				$("#myfav").html(data);
			});
		});

	<?php $this->endBlock()?>

	<?php
	\yii\web\YiiAsset::register($this);
	$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);