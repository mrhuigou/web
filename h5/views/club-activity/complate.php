<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='报名结果';
?>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="/">
            <i class="iconfont">&#xe63f;</i>
        </a>
        <div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
        </div>
        <a class="flex-item-2" href="<?= \yii\helpers\Url::to(['/user/index']) ?>">
            <i class="aui-icon aui-icon-people green f28"></i>
        </a>
    </div>
</header>
<section class="pt50">
    <div class="bg-wh">
        <img src="/assets/images/order-ok.png" class="w">
        <div class="tc pb5 " style="margin-top: -12px;">
            <h2 class="f24 green">报名成功</h2>
            <p class="f18 gray3 mt10 ">
                <a class="btn lbtn greenbtn" href="<?=$back_url?>">返回查看活动</a>
            </p>
        </div>
    </div>
</section>

