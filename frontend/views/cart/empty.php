<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='购物车';
?>
<div class="content">
    <div class="" style="min-width:1200px;">
        <div class="w1200 bc pt10">
            <!--购物车没商品时 start-->
            <p class="shopcart_empty">
                购物车内暂时没有商品，登录后，将显示您之前加入的商品<br />
                <a href="<?php echo \yii\helpers\Url::to(['/site/index'])?>" class="btn btn_small orgBtn mt5">去首页</a> 挑选喜欢的商品
            </p>

        </div>
    </div>
</div>