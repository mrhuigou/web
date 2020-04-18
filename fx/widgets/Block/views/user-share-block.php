<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/25
 * Time: 14:54
 */
?>
<?php if($model && $model->status){?>
<div class="mt5  whitebg p10 clearfix "><span class="fl "><i class="iconfont green" style="line-height: 14px;">&#xe62b;</i> 每日惠购团长</span><a class="red vm f12 fr " href="<?=\yii\helpers\Url::to(['/user-share/help'])?>">一键转发，轻松赚取分享收益<i class="iconfont f20" style="line-height: 14px;">&#xe6a9;</i> </a></div>
<div class="flex-col flex-center tc whitebg bdt bdb  p10 mb5">
	<a class="flex-item-6  flex-middle  p10 bdr " href="<?= \yii\helpers\Url::to(['/user-share/index']) ?>">
		<span class="red lh200">￥<?=$commission_total?floatval($commission_total):0?></span>
		<p><i class="iconfont org f20" style="line-height: 14px;">&#xe6ab;</i>
		已收益</p>
	</a>
	<a class="flex-item-6  flex-middle bdr  p10" href="<?= \yii\helpers\Url::to(['/user-share/order']) ?>">
		<span class="red lh200"><?=$order_total?floatval($order_total):0?></span>
		<p><i class="iconfont blue f20" style="line-height: 14px;">&#xe695;</i>
		订单数</p>
	</a>
<!--	<a class="flex-item-4  flex-middle  p10" href="--><?//= \yii\helpers\Url::to(['/user-share/follower']) ?><!--">-->
<!--		<span class="red lh200">--><?//=$follower_total?floatval($follower_total):0?><!--</span>-->
<!--		<p><i class="iconfont green f20" style="line-height: 14px;">&#xe6a8;</i>-->
<!--		粉丝数</p>-->
<!--	</a>-->
</div>
<!--<a class="btn lbtn bluebtn w"  href="--><?php //echo \yii\helpers\Url::to(['/affiliate-plan/index']).'?sourcefrom='.$model->code.'&type_code=DEFAULT'?><!--" >开始分享</a>-->
<a class="btn lbtn bluebtn w"  href="<?php echo '/affiliate-plan/index?sourcefrom='.$model->code.'&type_code='.$model->plan_type?>" >开始分享</a>
<?php }else{?>
<div class="mt5  whitebg p10 clearfix "><span class="fl "><i class="iconfont green" style="line-height: 14px;">&#xe62b;</i> 每日惠购团长</span><span class="red fn f12 fr ">申请开通，赚取分享收益</span></div>
<a href="<?php echo \yii\helpers\Url::to(['/user-share/index'])?>" class="w">
	<img src="/assets/images/share/banner2.jpg" class="w">
</a>
<?php }?>
