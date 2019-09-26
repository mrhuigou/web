<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/3
 * Time: 18:40
 */
?>
    <header class="fx-top bs-bottom whitebg lh44">
        <div class="flex-col tc">
            <a class="flex-item-2" href="/">
                <i class="aui-icon aui-icon-home green f28"></i>
            </a>
            <div class="flex-item-8 f16">
				<?= \yii\helpers\Html::encode($this->title) ?>
            </div>
            <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/user/index']) ?>">
                <i class="iconfont green f28">&#xe603;</i>
            </a>
        </div>
    </header>
    <div class="tc p20 f14">
        <?php if($user){?>
            <img src="<?=\common\component\image\Image::resize($user->photo,120,120)?>" width="120" height="120" class="img-circle mt30">
            <h3 class="pt5 f16"><?=$user->nickname?></h3>
            <p class="gray9 pt15 pb5">您的朋友邀你一起参加抽奖活动</p>
    <?php }else{?>
            <img src="/assets/images/logo-.jpg" width="120" height="120" class="img-circle mt30">
            <h3 class="pt5 f16">每日惠购</h3>
            <p class="gray9 pt15 pb5">关注家润公众号</p>
    <?php } ?>
        <p class="gray9 pt15 pb5" id="scan_code"></p>
        <p class="gray9 pt15 pb5">请长按识别关注公众号,进入抽奖活动！</p>
    </div>
<?php $this->beginBlock('JS_END') ?>
    var qr = qrcode(10, 'L');
    qr.addData('<?= $model->url ?>');
    qr.make();
    $("#scan_code").html(qr.createImgTag());
<?php $this->endBlock('JS_END') ?>
<?php
$this->registerJsFile("/assets/script/qrcode.js", ['depends' => \h5\assets\AppAsset::className()]);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_END);
?>