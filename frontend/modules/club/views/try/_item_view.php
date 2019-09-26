<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/7
 * Time: 20:55
 */
use \common\component\image\Image;
?>
<div class="bd p10 clearfix mb10 hovercr">
    <div class="fl w">
        <div style="margin-left: 215px;margin-right: 200px;" class="">
            <h2 class="f16 mb10 mt10"><?=\yii\helpers\Html::encode($model->title)?></h2>
            <p class="mb10 green"><?=$model->product?$model->product->description->meta_description:''?></p>
            <p class="mb10 org0"><?=$model->end_datetime?> 准时开奖，限量<?=$model->quantity?> 份</p>
            <p class="mb15 org0">成功邀请 <?=$model->limit_share_user?$model->limit_share_user:0?> 位好友参与免费试吃活动，可直接获得免费试吃机会哦！！</p>
            <?php if(time()<strtotime($model->begin_datetime)){?>
                <a class="btn btn_middle disableBtn" style="border:1px solid #d60918;"> 尚未开始</a>
            <?php }elseif(time()>strtotime($model->end_datetime)){?>
            <a class="btn btn_middle disableBtn" style="background-color: #999;color: white"> 试吃结束</a>
            <?php } else{ ?>
            <a class="btn btn_middle greenbtn" href="<?=\yii\helpers\Url::to(['/club/try/info','id'=>$model->id])?>"> 我要报名</a>
            <?php } ?>
            <?php if($model->product){ ?>
            <a class="btn btn_middle redBtn"  target="_blank" href="<?=\yii\helpers\Url::to(['/product/index','product_base_code'=>$model->product->product_base_code,'shop_code'=>$model->product->store_code])?>"> 直接购买</a>
            <?php } ?>
        </div>
    </div>
    <a style="margin-left: -100%;" class="fl"  target="_blank" href="<?=\yii\helpers\Url::to(['/club/try/info','id'=>$model->id])?>"><img width="200" height="200" class="db" alt="tu" src="<?=Image::resize($model->product?$model->product->image:'',200,200)?>"></a>
    <div style="margin-left:-200px;" class="fl tc bdl pl30 pt25 pb30">
        <p class="lh200 f14">微信扫一扫，分享给好友</p>
        <p><img width="98" height="98" src="<?=\yii\helpers\Url::to(['/club/qrcode/index','data'=>'http://m.mrhuigou.com/club-try/detail?id='.$model->id,true])?>"></p>
    </div>
</div>