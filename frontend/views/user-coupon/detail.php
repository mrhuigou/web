<?php
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;

$this->title = '我的优惠券详情';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="" style="min-width:1100px;">
	<div class="w1100 bc ">
		<!--面包屑导航-->
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			'tag' => 'p',
			'options' => ['class' => 'gray6 pb5 pt5'],
			'itemTemplate' => '<a class="f14">{link}</a> > ',
			'activeItemTemplate' => '<a class="f14">{link}</a>',
		]) ?>
		<div class="bc  clearfix simsun">
			<div class="fl w100 mr10 menu-tree">
				<?= frontend\widgets\UserSiderbar::widget() ?>
			</div>
			<div class="fl w990 ">
				<div class="graybg bd  mb10 clearfix">
					<a href="#" class="fl"><img src="<?=\common\component\image\Image::resize($coupon->coupon->image_url,100,100)?>" alt="图标" class="db" width="110" height="110"></a>

					<div class="fl p15 w220">
						<div class="f30 red icon-pass lh">
							<?php if ($coupon->coupon->type=='F'){ ?>
								￥<?=number_format($coupon->coupon->discount,2,'.','')?>
							<?php }else{ ?>
								<?=$coupon->coupon->getRealDiscount()?>折
							<?php }?>
							<?php if($coupon->is_use){ ?>
								<i class="iconfont gray">&#xe67c;</i>
							<?php }elseif(strtotime($coupon->end_time)<time()){?>
								<i class="iconfont yellow">&#xe67b;</i>
							<?php }?>
						</div>
						<h2 class="f14 "><?=$coupon->coupon->name?></h2>
						<div class="pt10"><?=date('m/d H:i:s',strtotime($coupon->start_time))?> ~  <?=date('m/d H:i:s',strtotime($coupon->end_time))?></div>
					</div>

					<div class="fr p15 pt20 ml20 clearfix">
						<a href="<?=\yii\helpers\Url::to(['/coupon/index'])?>" class="p10 db fl mr10 clearfix tc w85">
							<i class="iconfont f40 red">&#xe635;</i>
							<p>领取更多优惠券</p>
						</a>
					</div>

				</div>
				<h2 class="f14 mt30 mb10 f14">
					<?=$coupon->coupon->comment?$coupon->coupon->comment:$coupon->coupon->description?>
				</h2>
				<div class="whitebg ">
						<!--列表-->
							<?php
							ListView::begin([
								'dataProvider'=>$dataProvider,
								'itemView'=>"_listproductview",
								'options'=>['class' => 'list-view clearfix lr-m5 '],
								'layout'=>'{items}</div><div class="tc m10">{pager}',
								'itemOptions'=>['class'=>'pw20 fl'],
								'pager'=>[
									'maxButtonCount'=>10,
									'nextPageLabel'=>Yii::t('app','下一页'),
									'prevPageLabel'=>Yii::t('app','上一页'),
								],
								'emptyText'=>'提示：您还没有任何订单信息!',
								'emptyTextOptions'=>['class' => 'tc bd'],
							]);?>
							<?php ListView::end();?>
					</div>
			</div>
		</div>
	</div>
</div>
