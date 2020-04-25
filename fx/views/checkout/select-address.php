<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/14
 * Time: 11:17
 */
?>
<?php if($model){?>
	<div class="flex-col " >
		<div class="flex-item-10 select_address" >
			<p><em class="confirm-username"><?=$model->firstname?></em><em class="confirm-tel ml10"><?=$model->telephone?></em></p>
			<p class="confirm-zone"><?=$model->citys?$model->citys->name:''?>-<?=$model->district?$model->district->name:""?></p>
			<p class="confirm-address"> <?=$model->address_1?> </p>
		</div>
		<div class="flex-item-2 tr pt20 green">
			修改<i class="iconfont f14 ">&#xe60b;</i>
		</div>
	</div>
<?php }else{?>
	<div class="select_address">
		<a class="db p20  rarrow whitebg f14 tc" href="javascript:;"><span class="iconfont fb">&#xe60c;</span>创建您的收货地址 </a>
	</div>
<?php }?>
