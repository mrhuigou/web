<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/18
 * Time: 14:47
 */
?>
<?php if($coupons){?>
	<?php foreach ($coupons as $value){?>
        <div class="order-ok fb">
            <p class="f20 red">恭喜你</p>
            <p class="f18">获得1张现金券</p>
            <p class="red mt2 mb50">
                <i class="f30">￥</i><span class="f50"><?=floatval($value->coupon->discount)?></span>
            </p>
            <p class="wh f16 pt20 pb20">
                有效期到<?=date('Y-m-d',time()+$value->coupon->expire_seconds)?> <br>
	            <?=$value->coupon->getDescription()?>
            </p>
            <a href="javascript:;" class="btn btn-l btn-yellow- btn-pill pw50 coupon-apply" style="color:#ff2d4b;" data-content="<?=$value->coupon->code?>">点击领取</a>
        </div>
	<?php }?>
	<?php $this->beginBlock('JS_END') ?>
    $(".coupon-apply").on("click",function(){
    $.showLoading("正在加载");
    $.post('/coupon/complate-coupon',{coupon_code:$(this).attr("data-content")},function(res){
    $.hideLoading();
    if(res.status){
    $.toast(res.message);
    setTimeout("location.href='/user-coupon/index'", 500);
    }else{
    $.alert(res.message);
    }
    },'json');
    });
	<?php $this->endBlock() ?>
	<?php
	\yii\web\YiiAsset::register($this);
	$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
	?>
<?php }else{?>
    <?php if($chance=\h5\widgets\Block\Game::widget(['customer_id'=>$model->customer_id])){?>
    <?=$chance?>
	<?php }else{?>
        <?php if(!Yii::$app->session->get('source_from_agent_wx_xcx')){?>
        <div class="  p5 tc ">
        <a href="javascript:;" class="w share-guide">
            <img src="/assets/images/tuijian.jpg" class="pw80">
        </a>
        </div>
        <?php }?>
        <?php }?>
<?php }?>

