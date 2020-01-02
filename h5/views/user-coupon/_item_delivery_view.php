<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/27
 * Time: 21:25
 */
?>
<?php
$can_not_use_arr = ['ECP170928002','ECP170928003','ECP170929002','ECP170929003','ECP170929004','ECP170929005','ECP170929006','ECP170929007','ECP170929008'];
?>
<?php
$can_not_use = false;
if(in_array($model->coupon->code,$can_not_use_arr) && time() < strtotime('2017-10-10 00:00:00')){
    $can_not_use = true;
}?>
<?php if($can_not_use){?>
    <a href="javascript:void(0)" onclick="alert('母婴类折扣券，10月10日即可查看')">
<?php }else{?>
<a href="<?=\yii\helpers\Url::to(['/coupon/view_delivery','id'=>$model->coupon_id])?>">
<?php }?>

<div class="br5 bg-wh mb10 activity-1-coupon">
    <div class="flex-col bd-d-b">
        <div class="flex-item-7">
            <h3 class="red pt5 mt1"><?=$model->coupon->name?></h3>
            <span class="gray9 "><?=$model->coupon->comment?$model->coupon->comment:$model->coupon->description?></span>
	        <?php if($model->coupon->max_discount){?>
                <span class="red">最高抵用￥<?=$model->coupon->max_discount?></span>
	        <?php } ?>
        </div>
        <div class="flex-item-5 red tr">
            <?php if($model->coupon->model!=='BUY_GIFTS'){?>
			<?php if($model->coupon->type=='F'){?>
                <span class="f25">￥</span><span class="f40"><?=$model->coupon->getRealDiscount()?></span>
			<?php }else{?>
                <span class="f40"><?=$model->coupon->getRealDiscount()?></span><span class="f25">折</span>
			<?php } ?>
            <?php }?>
        </div>
    </div>
    <div class="f14 pt5">
        <span class="red fr">去使用&gt;</span>
        <span class="gray9">截止：<?=date('m-d',strtotime($model->start_time))?>~<?=date('m-d H:i',strtotime($model->end_time))?></span>
    </div>
</div>
</a>