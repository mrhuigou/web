<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title ='订单详情';
?>
    <header class="header w" id="header">
        <a href="javascript:history.back();" class="his-back">返回</a>
        <h2><?= Html::encode($this->title) ?></h2>
        <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
    </header>

    <section class="veiwport">
        <div class="fool white clearfix">
            <em class="fl mr15 iconfont">&#xe61f;</em>
            <div class="fl w-per77 f12 lh150 clearfix">
                <div class="fl">
                    <p >订单号：<?=$model->order_no?></p>
                    <p >订单金额:￥<?= number_format($model->total,2);?></p>
                    <?php if($model->orderPayments) { ?>
                        <p ><span >支付方式：</span>
                        <span class="fb">
							<?php foreach($model->orderPayments as $key=>$pay){ echo (($key>0)?'，':'').$pay['payment_method'].($pay['total']>0?('('.number_format($pay['total'],2).')'):'');}?>
							</span>
                        </p>
                        <div class="line_dash"></div>
                    <?php } ?>
                </div>
                </div>
                <div class="fr">
                    <p class="f14 mb5 "><?=$model->orderStatus->name;?></p>
                </div>
        </div>
        <div class="whitebg bdb p10 pt15 pb15 pr  w">
            <div class="pr50 mr30">
                <p class="mxh35 mb5">
                    <a href="<?=Url::to(['/club-activity/detail','id'=>$model->activity->activity_id])?>"><?=$model->activity->activity_name;?></a>
                </p>
                <div class="gray9 ">
                    <p class="clearfix">
                        <i class="fr mr5 metro-basic bluebg-">
                            <?=$model->activity->total==0?"免费":($model->activity->total==2?"AA制":"收费")?>
                        </i>
                        <i class="iconfont f14 mt2">&#xe61f;</i>
                        <span><?=$model->activity->activity_item_name?></span>
                    </p>
                    <p class="mt2 clearfix">
                        <span class="fr pr5 red">￥<?=$model->activity->total?></span>
                        <i class="iconfont f14 mt2">&#xe62b;</i>
                        <span><?=$model->activity->quantity?>人</span>
                    </p>
                </div>
            </div>
            <div class="pa-rt t15 r10">
                <a href="<?=Url::to(['/club-activity/detail','id'=>$model->activity->activity_id])?>"> <img src="<?=\common\component\image\Image::resize($model->activity->activity->image,50,50,9);?>" alt="<?=$model->activity->activity_name;?>"  width="75" height="75"></a>
            </div>
        </div>
        <div class="whitebg p5">
            <p class="lh200"><span class="fb p5">姓名:</span><span><?=$model->firstname?></span></p>
            <p class="lh200"><span class="fb p5">电话:</span><span><?=$model->telephone?></span></p>
        <?php if($model->activity->option){?>
            <?php foreach($model->activity->option as $option){ ?>
                <p class="lh200"><span class="fb p5"><?=$option->activity_option_name?>:</span><span><?=$option->activity_option_value?></span></p>
            <?php } ?>
        <?php } ?>
        </div>
        <?php if($model->orderScan ){ ?>
        <div class="br5 whitebg  bdt p10 pr mb10 lh200 tc">
            <h2 class="fb f14">报名凭证 <em class="fb red"><?=$model->orderScan->status?"[已经激活]":""?></em></h2>
            <p ><img src="<?=\yii\helpers\Url::to(['/club-activity/qrcode','code'=>$model->orderScan->scan_data])?>" >
            <p> 长按图片可以分享给朋友</p>
            <p class=" f14 mb10 fb">现场验票时请出示</p>
        </div>
        <?php } ?>
        <?php if($model->order_status_id == 1){ ?>
            <div class="   tc  fx-bottom whitebg p10  bs-top" style="z-index: 1000">
                <a href="<?=\yii\helpers\Url::to(['/payment/index','trade_no'=>$model->order_id,'showwxpaytitle'=>1],true)?>" class="fl btn mbtn greenbtn">立即支付</a>
                <?= Html::a('取消订单', ['cancel', 'order_id' => $model->order_id], [
                    'class' => 'fr btn mbtn graybtn ',
                    'data' => [
                        'confirm' => '您确认要取消吗？',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        <?php }?>
    </section>