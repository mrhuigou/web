<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='安全中心';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
</header>
<section class="veiwport">
        <div>
            <?php if(!empty($model->password)){?>
                <a href="<?php echo \yii\helpers\Url::to("/user/security-update-password")?>" class="choco-piece clearfix">
                    <span class="fl iconfont img" style="">&#xe647;</span>
                    <div class="fl">
                        <h3>登录密码</h3>
                        <span class="gray9 f12">已设置</span>
                    </div>
                    <i class="iconfont fr f18 mt10">&#xe60b;</i>
                </a>
            <?php }?>
            <a href="<?php if($model->idcard_validate !=2){echo \yii\helpers\Url::to("/user/security-ums-auth");}else{echo "javascript:void(0)";}?>" class="choco-piece clearfix">
                <span class="fl iconfont img">&#xe603;</span>
                <div class="fl">
                    <h3>信用认证</h3>
                    <?php if($model->idcard_validate ==2){?>
                        <span class="gray9 f12">已认证</span>
                    <?php }elseif($model->idcard_validate ==3){?>
                        <span class="red f12">认证失败</span>
                    <?php }else{?>
                        <span class="red f12">未认证</span>
                    <?php }?>
                </div>
                <?php if($model->idcard_validate !=2){ ?>
                    <i class="iconfont fr f18 mt10">&#xe60b;</i>
                <?php }else{?>
                    <i class="iconfont fr f18 mt10"></i>
                <?php }?>
            </a>
            <a href="<?php echo \yii\helpers\Url::to(["/user/security-set-telephone"]);?>" class="choco-piece clearfix">
                <span class="fl iconfont img">&#xe61e;</span>
                <div class="fl">
                    <h3>手机验证</h3>
                    <?php if($model->telephone_validate == 1){?>
                        <span class="gray9 f12">已验证：<?php echo \common\component\Helper\Helper::str_mid_replace($model->telephone);?></span>
                    <?php }else{?>
                        <span class="red f12">未验证</span>
                    <?php }?>
                </div>
                <i class="iconfont fr f18 mt10">&#xe60b;</i>
            </a>

            <a href="<?php echo \yii\helpers\Url::to(["/user/security-set-email"])?>" class="choco-piece clearfix">
                <span class="fl iconfont img">&#xe649;</span>
                <div class="fl">
                    <h3>邮箱验证</h3>
                    <?php if($model->email_validate == 1){?>
                        <span class="gray9 f12">已验证：<?php echo \common\component\Helper\Helper::str_mid_replace($model->email);?></span>
                    <?php }else{?>
                        <span class="red f12">未验证</span>
                    <?php }?>

                </div>
                <i class="iconfont fr f18 mt10">&#xe60b;</i>
            </a>
            <a href="<?php echo \yii\helpers\Url::to(["/user/security-update-paymentpwd"])?>" class="choco-piece clearfix">
                <span class="fl iconfont img">&#xe64a;</span>
                <div class="fl">
                    <h3>支付密码</h3>
                    <?php if(!empty($model->paymentpwd)){?>
                        <span class="gray9 f12">已设置</span>
                    <?php }else{?>
                        <span class="red f12">未设置</span>
                    <?php }?>
                </div>
                <i class="iconfont fr f18 mt10">&#xe60b;</i>
            </a>
        </div>
    </section>
<?= h5\widgets\MainMenu::widget(); ?>