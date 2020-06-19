<div class="page-sidebar navbar-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu">
        <li class="sidebar-toggler-wrapper">
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <div class="sidebar-toggler hidden-phone">
            </div>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        </li>
        <li class="start active ">
            <a href="/">
                <i class="fa fa-home"></i>
					<span class="title">
						控制台
					</span>
            </a>
        </li>

        <li class="">
            <a href="<?= \yii\helpers\Url::to('/affiliate-scans/index')?>">
                <i class="fa fa-briefcase"></i>
                    <span class="title">
                        推广码管理
                    </span>
            </a>
        </li>
        <li class="">
            <a href="<?= \yii\helpers\Url::to('/customer/index')?>">
                <i class="fa fa-briefcase"></i>
                    <span class="title">
                        推广用户
                    </span>
            </a>
        </li>
        <li class="">
            <a href="<?= \yii\helpers\Url::to('/order/index')?>">
                <i class="fa fa-briefcase"></i>
                <span class="title">
                        推广订单
                    </span>
            </a>
        </li>
        <li class="">
            <a href="<?= \yii\helpers\Url::to('/affiliate-transaction/index')?>">
                <i class="fa fa-briefcase"></i>
                    <span class="title">
                        帐户佣金
                    </span>
            </a>
        </li>
        <?php
        if(!Yii::$app->user->isGuest){?>
            <?php if(Yii::$app->user->getId() == 9){?>
                <li class="">
                    <a href="<?= \yii\helpers\Url::to('/affiliate-transaction-statement/index')?>">
                        <i class="fa fa-briefcase"></i>
                        <span class="title">
                            结算单
                        </span>
                    </a>
                </li>
            <?php  } ?>
        <?php } ?>

    </ul>
    <!-- END SIDEBAR MENU -->
</div>