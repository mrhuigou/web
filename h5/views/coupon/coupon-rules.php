<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = $coupon_rules->name;
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<style type="text/css">
    .new-coupon{
        width:32rem; height: 10rem; padding-top:0.8rem; padding-left:3.1rem; margin-bottom:0.5rem;
    }
    .new-coupon em{
        color:#666;
        font-size:1rem;
    }
    .new-coupon .p1{
        font-size:1.1rem;
        font-weight: bold;
    }
    .new-coupon .p2{
        font-weight: bold;
        font-size: 1.1rem; margin-bottom:0.2rem;
    }
    .new-coupon .a{
        color: #fff;
        background-color: .bg-red;
        display: block;
        text-align: center;
        width:7.3rem;height:2rem;line-height:2rem;font-size:1.4rem;
    }
    .new-coupon .p3{
        font-size: 0.9rem; margin-top:0.27rem;
    }

</style>
<section class="veiwport">
    <div class="bg-wh mb70">
        <?php if($coupon_rules){?>

            <img src="<?php echo \common\component\image\Image::resize($coupon_rules->img_url);?>" class="w mb10" />
            <?php if($coupon_rules_details){?>
                <?php foreach ($coupon_rules_details as $coupon_rules_detail){?>
                    <?php $coupon = $coupon_rules_detail->coupon;?>
                    <?php if($coupon){?>
                    <?php if(!$coupon_rules_detail->img_url){
                            $img_url = $coupon->image_url;
                        }else{
                            $img_url = $coupon_rules_detail->img_url;
                        }
                    ?>
                    <div class="new-coupon" style="background: url('<?php echo \common\component\image\Image::resize($img_url)?>'); background-size: 100%;">
                        <em>
                            <?php echo $coupon->comment?>
                        </em>
                        <p class="p1">
                            <?php echo $coupon->name?>
                        </p>
                        <p class="p2">

                        </p>
                        <a href="javascript:void(0);" class="a CouponRulesApply" data-content="<?php echo $coupon_rules_detail->coupon_rules_detail_id?>">
                            立即领取
                        </a>
                        <p class="p3">
                            <?php if(strtoupper($coupon->date_type) == 'DAYS'){
                                $days = $coupon->expire_seconds/(3600*24);
                                $notice = '有效期:自领取日'.$days.'日内有效';
                            }else{
                                $notice = '有效期至'.$coupon->date_end;
                            }
                            echo $notice
                            ?>

                        </p>
                    </div>
                        <?php }?>
                <?php }?>
            <?php }?>

        <?php }?>

    </div>


</section>
<script>
<?php
$this->beginBlock('JS_SKU')
?>
    $(".CouponRulesApply").on('click',function () {
        var detail_id = $(this).attr('data-content');
        $.showLoading("正在加载");
        var action = 'apply';
        $.post('<?=\yii\helpers\Url::to('/coupon/apply-coupon-rules',true)?>',{detail_id:detail_id,action:action,_csrf:"<?php echo Yii::$app->request->csrfToken;?>"},function(data){
            $.hideLoading();
            if(data.status == 1){
//                $.toast(data.message);

                $.confirm("所有新手福利券，每人仅能领取一次，是否确认领取此券？",'友情提示',function(){
                    action = 'add';
                    $.showLoading("正在加载");
                    $.post('<?php echo \yii\helpers\Url::to(["/coupon/apply-coupon-rules"])?>',{detail_id:detail_id,action:action,_csrf:"<?php echo Yii::$app->request->csrfToken;?>"},function(){
                        $.hideLoading();
                        $.alert(data.message)
                    });
                });
            }else{
                $.alert(data.message)
            }
        },'json');
    });

<?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS_SKU'], \yii\web\View::POS_READY);
?>

<?= h5\widgets\MainMenu::widget(); ?>
<?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/zhqd/logo_300x300.jpeg';
}else{
    $share_image = Yii::$app->request->getHostInfo().'/assets/images/mrhuigou_logo.png';
}?>
<?=\h5\widgets\Tools\Share::widget([
    'data'=>[
        'title' =>'终于等到你，小鲜肉们接收大礼包!',
        'desc' => '新鲜酸奶全场半价，上午下单下午到。',
        'link' => \yii\helpers\Url::to(['/share/subscription', 'share_user_id' => Yii::$app->user->getId(),'redirect'=>'/site/index'], true),
        'image' => $share_image
    ]
])?>

