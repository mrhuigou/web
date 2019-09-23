<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\grid\GridView;
use yii\helpers\Url;
?>

<div class="graybg2 pb10" style="min-width:1100px;">
	<div class="w1100 bc pt15">
		<!--面包屑导航-->
		<?php 
			$this->title = '我的宝箱';
			$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
			$this->params['breadcrumbs'][] = $this->title;
			?>
			<?= Breadcrumbs::widget([
			    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			    'tag'=>'p',
			    'options'=>['class'=>'gray6 mb15'],
			    'itemTemplate'=>'<a class="f14">{link}</a> > ',
			    'activeItemTemplate'=>'<a class="f14">{link}</a>',
			]) ?>
		<div class="user_center clearfix whitebg">

			<div class="">
				<p class="whitebg p15 bdb">
					<span class="org icon_light">友情提示：宝箱中的物品不能单独配送，只能随附您的购物订单一起发送。谢谢，祝您购物愉快！</span>
				</p>
				<div class="p10">
				<table cellpadding="0" cellspacing="0" class="shopcart_list w bdb">
					<?= GridView::widget([
					        'dataProvider' => $dataProvider,
					        'showHeader'=>true,
					        'tableOptions'=>['class'=>'shopcart_list w bdb'],
					        'layout'=>"{items}\n<div class=\"tc p20\">{pager}</div>",
					        'emptyText'=>'提示：您还没有任何宝箱信息!',
					        'emptyTextOptions'=>['class' => 'tc bd'],
					        'columns' => [
					            //显示的字段
					            //code的值
						        ['attribute'=>'宝物名称','value'=>'product.description.name'],
						        ['attribute'=>'获取途径','value'=>'treasure.title'],
						        ['attribute'=>'价格','value'=>'product.Price'],
						        ['attribute'=>'获取日期','value'=>'date_added'],
						        ['attribute'=>'到期日期','value'=>function($model){
						        	if ($model->date_expired == '0000-00-00 00:00:00') {
						        		return '无限制';
						        	}else{
						        		return $model->date_expired;
						        	}
						        }],
						        ['attribute'=>'是否在线领取','value'=>function($model){
						        	if ($model->is_online == 1) {
						        		return '是';
						        	}else{
						        		return '否';
						        	}
						        }],
						        ['attribute'=>'状态','value'=>function($model){
						        	if ($model->status == 1) {
						        		return '已占用';
						        	}elseif ($model->status == 2){
						        		return '已使用';
						        	}elseif ($model->status == 0 && ($model->date_expired > date("Y-m-d H:i:s",time())) || $model->date_expired == '0000-00-00 00:00:00'){
						        		return '可使用';
						        	}else{
						        		return '已失效';
						        	}
						        }],
					        ],
					    ]); ?>			
				</table>
				</div>
				
				<div class="p10">
				<div class="yellowBox pt10 pb10 pl20 pr20 mt20 mb5">
					<h3 class="f14 fb">特别提示：</h3>
					<div class="line_dash mt5 mb5"></div>
					<p>（1）当您从购物车中去结算时，在订单确认页面可以选择（或输入）您的优惠券券号，获得相应的优惠。</p>
					<p>（2）每个订单限使用一张优惠券，优惠券限仅使用一次。</p>
					<p>（3）优惠券有不同的类型，如仅限某品牌、某品类使用的优惠券等。</p>
					<p>（4）请注意：优惠券是有过期时间的哦！请在过期之前使用。</p>
					<p>（5）若您获得满额返券的订单发生退货，因该订单所返的所有优惠券都将被取消。</p>
					<p>（6）当您使用优惠券购买的商品发生退货时，将不会退还该优惠券分摊优惠至每个商品中的金额。</p>
				</div>
				</div>
				
			</div>
		</div>
	</div>
</div>	
