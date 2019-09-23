<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = '首页 - 家润慧生活（365jiarun.com）-青岛首选综合性同城网购-发现达人体验-分享同城生活';
?>
<!--content-->
<div id="">
    <div style="background-color:#8da93d;">
        <img src="../assets/images/new/banner1.jpg" class="db w" />
        <img src="../assets/images/new/banner2.jpg" class="db w" />
    </div>

    <div class="pr">
        <!-- 新会员礼包 -->
        <a href="javascript:void(0)" class="newGift db cur share_pop">

            <img src="../assets/images/new/gift2.png" class="db" title="点击扫描二维码q" />
        </a>
        <!-- 弹层 -->

    </div>
    <img src="../assets/images/new/img1.jpg" class="db bc" />
    <!-- 广告位 -->
    <?php if($ad1){ ?>
        <?php foreach ($ad1 as $key=> $value){?>
            <?php if($key == 0){?>
                <img src="<?=\common\component\image\Image::resize($value->source_url)?>" class="cp db bc g-tri" title="点击扫描二维码" />
                <!-- 弹层 -->
                <div class="g-con" style="display: none;">
                    <div class="tc">
                        <img src="<?php echo \yii\helpers\Url::to(['/site/get-qrcode','id'=>$value->advertise_detail_id],true)?>" alt="这里是二维码">
                    </div>
                </div>
            <?php }?>
        <?php }?>
    <?php }?>



    <div class="w1000 bc clearfix pb30">
        <?php if($des1){?>
            <?php foreach ($des1 as $key=> $value){?>
                <div class="g-box viewport-flip">
                    <a href="javascript:void(0);" class="g-list a flip out"><img src="<?php echo \yii\helpers\Url::to(['/site/get-qrcode','id'=>$value->advertise_detail_id],true)?>" alt="二维码" class="w db mt30" /></a>
                    <a href="javascript:void(0);" class="g-list b flip"><img src="<?=\common\component\image\Image::resize($value->source_url)?>" alt="图片" class="w db" /></a>
                </div>
            <?php }?>
        <?php }?>

    </div>

    <div style="background-color:#e4ebd0;">
        <img src="../assets/images/new/img2.jpg" class="db bc" />
        <img src="../assets/images/new/img3.jpg" class="db bc" />
    </div>

    <img src="../assets/images/new/img4.jpg" class="db bc" />

</div>
<?php $this->beginBlock('JS_END') ?>
    $('.newGift').scrollFix(40, "top");


    // 二维码翻转
    $(".g-box").mouseenter(function() {

    // 切换的顺序如下
    // 1. 当前在前显示的元素翻转90度隐藏, 动画时间225毫秒
    // 2. 结束后，之前显示在后面的元素逆向90度翻转显示在前
    // 3. 完成翻面效果

    var _this = $(this),
    a = _this.find(".a"),
    b = _this.find(".b");

    b.addClass("out").removeClass("in").fadeOut();

    setTimeout(function() {
    a.addClass("in").removeClass("out").fadeIn();
    }, 225);

    return false;

    });

    $(".g-box").mouseleave(function(){

    var _this = $(this),
    a = _this.find(".a"),
    b = _this.find(".b");

    a.addClass("out").removeClass("in").fadeOut();

    setTimeout(function() {
    b.addClass("in").removeClass("out").fadeIn();
    }, 225);

    return false;
    })



    // 打广告位点击
    $(".g-tri").click(function(){

    layer.open({
    type: 1,
    closeBtn: 2,
    title: false,
    shadeClose: true,
    content: $('.g-con').html()
    //btn: ['确认']
    });
    })


<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>