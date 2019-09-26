<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='领优惠券';
?>
<style>
    .cur a{
        color: green;
        border-bottom: 2px solid green;
    }
</style>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<?php
$can_not_use_arr = ['ECP170928002','ECP170928003','ECP170929002','ECP170929003','ECP170929004','ECP170929005','ECP170929006','ECP170929007','ECP170929008'];

?>
<section class=" pb50">
        <img src="/assets/images/coupon.jpg" alt="领折扣券" class="db w mb5">
    <?php if($model){?>
        <div class="menu-tab whitebg w" >
            <p id="nav" class="w p5">
                <?php foreach($model as $data){ ?>
                <span class="p5 lh200 "><a href="#<?=$data['base']->code?>" class="nowrap"><?=$data['base']->name?></a></span>
                <?php } ?>
            </p>
        </div>
    <?php } ?>
    <?php if($model){?>
    <div class="panels">
        <?php foreach($model as $data){ ?>
            <div class="tit1 orgtit1">
                <h2><?=$data['base']->name?></h2>
            </div>
        <div class="panel" id="<?=$data['base']->code?>">
            <?php foreach($data['list'] as $value){?>
            <div class="flex-col m5 br5 bg-wh ">
                <div class="flex-item-3 p5">
                    <img src="<?=\common\component\image\Image::resize($value->image_url,300,300)?>" alt="<?=$value->name?>" width="80" height="80">
                </div>
                <div class="flex-item-6 p5">
                    <?php if($value->model =='BUY_GIFTS'){?>
                        <span class="f18 red">赠品券</span>
                    <?php }else{?>
                        <span class="f20 red"><?php if ($value->type=='F'){ ?>
                                ￥<?=number_format($value->discount,2,'.','')?>
                            <?php }else{ ?>
                                <?=number_format($value->getRealDiscount(),2,'.','')?>折
                            <?php }?></span>

                    <?php }?>
                    <p class="f12 mt5"><?=$value->name?></p>
                    <p class=" f10"><?=$value->comment?$value->comment:$value->description?></p>
                </div>
                <?php
                $can_not_use = false;
                if(in_array($value->code,$can_not_use_arr) && time() < strtotime('2017-10-10 00:00:00')){
                    $can_not_use = true;
                }?>
                <div class="flex-item-3 tc bg-red  pt20 pb20">
                    <?php if($value->getUsedStatus(Yii::$app->user->getId())){?>
                    <a href="javascript:;" class="btn graybtn sbtn f12 coupon-item-btn" data-id="<?=$value->coupon_id?>" data-content="<?=$value->code?>">立即领取</a>
                    <?php if($can_not_use){?>
                    <a href="javascript:void(0)" onclick="alert('母婴类折扣券，10月10日即可查看')"></a>
                        <?php }else{?>
                            <a href="<?=\yii\helpers\Url::to(['/coupon/view','id'=>$value->coupon_id],true)?>" class="btn greenbtn sbtn f12 coupon-view-btn white " style="display: none;">马上使用</a>
                            <?php }?>

                        <?php }else{ ?>
                        <?php if($can_not_use){?>
                                <a href="javascript:void(0)" onclick="alert('母婴类折扣券，10月10日即可查看')"></a>
                            <?php }else{?>
                                <a href="<?=\yii\helpers\Url::to(['/coupon/view','id'=>$value->coupon_id],true)?>" class="btn greenbtn sbtn f12 coupon-view-btn white ">马上使用</a>
                            <?php }?>

                        <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</section>
<?= h5\widgets\MainMenu::widget(); ?>
<?php
$this->beginBlock('JS_SKU')
?>
$(".coupon-item-btn").on('click',function(){
var obj=$(this);
$.showLoading("正在加载");
$.post('<?=\yii\helpers\Url::to('/coupon/ajax-apply',true)?>',{coupon_code:$(this).data('content')},function(data){
$.hideLoading();
if(data.status){
obj.hide();
obj.siblings('.coupon-view-btn').show();
}else{
$.toast(data.message);
}
},'json');
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_SKU'], \yii\web\View::POS_READY);
?>
<?php
     if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
        $share_config = [
            'title' => '智慧青岛-'.$this->title,
            'desc' => "智慧青岛-生活用品，物美价廉，当天订单，当天送。",
            'link' => Yii::$app->request->getAbsoluteUrl(),
             'image' =>'https://m.mrhuigou.com/images/gift-icon.jpg'
        ];
    }else{
        $share_config = [
            'title' => '家润-'.$this->title,
            'desc' => "家润网-生活用品，物美价廉，当天订单，当天送。",
            'link' => Yii::$app->request->getAbsoluteUrl(),
            'image' => 'https://m.mrhuigou.com/assets/images/logo_300x300.png'
        ];
    }
?>

<?=\h5\widgets\Tools\Share::widget([
    'data'=>$share_config
])?>
<?=\h5\widgets\Block\Share::widget();?>