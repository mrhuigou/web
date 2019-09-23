<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '充值结果';
?>
<?= h5\widgets\Header::widget(['title' => $this->title]) ?>
    <section class="veiwport">
        <div class="tc p10">
            <i class="iconfont fb green f50">&#xe61a;</i>
            <h1 class=" lh150 fb f14">充值成功</h1>
            <p class="lh150 f14">充值金额：￥<?= $model->value ?></p>
        </div>
		<?php if ($model->card_code == 'Hisense' && $model->value > 150 && time() < strtotime('2016-09-16')) { ?>
            <div class="graybg tc red lh200 w p10">
                <h2>您的中秋福利《月饼兑换券》已存用您的帐户中。</h2>
                <p>有效期截止至2016年09月17日。</p>
                <p>中秋福利月饼已添加到购物车。</p>
                <p>您可提交订单时使用此优惠券折扣商品金额。</p>
                <div class="m10 bc tc ">
                    <a href="/act/EPP000814.html" class="btn mbtn greenbtn ">企业团购专区</a>
                </div>
            </div>
		<?php } ?>
    </section>
<?= h5\widgets\MainMenu::widget(); ?>