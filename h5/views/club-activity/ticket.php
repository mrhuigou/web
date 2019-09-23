<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use \common\component\image\Image;
$this->title ='活动凭证';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18">活动凭证</h2>
    <a href="<?=\yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>
<section class="veiwport ">
    <div class="p20">
    <?php
    foreach($models as $model){
        ?>
        <p class="lh200 fb clearfix ">交易时间：<?=$model->update_at?>
         <?php if(strtotime($model->activity->signup_end)>time()){?>
                <a href="<?=\yii\helpers\Url::to(['/club-activity/cancel','id'=>$model->id])?>"  class=" fr red">取消报名</a>
            <?php } ?>
        </p>
   <?php
    if($model->tickets){
        foreach($model->tickets as $ticket){
    ?>
    <div class="br5 whitebg  p10 pr mb10 lh200 tc">
        <h2 class="fb f14  ">报名凭证<?=$ticket->status?"(<em class='red'>已经使用</em>)":""?></h2>
        <p>
            <?php if ($model->activityItems){ ?>
            <?=$model->activityItems->name?><?=$model->activityItems->fee?>
            <?php }else{ ?>
               参与报名
           <?php } ?>
        </p>
        <p ><img src="<?=\yii\helpers\Url::to(['/club-activity/qrcode','code'=>$ticket->code])?>" >
        <p> 长按图片可以分享给朋友</p>
        <p class=" f14 mb10 fb">现场验票时请出示</p>

    </div>
    <?php } } } ?>
    </div>
</section>