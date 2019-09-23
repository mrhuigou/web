<!-- BEGIN TOP NAVIGATION MENU -->
<ul class="nav navbar-nav pull-right">
    <!-- BEGIN NOTIFICATION DROPDOWN -->

    <!-- END NOTIFICATION DROPDOWN -->
    <!-- BEGIN INBOX DROPDOWN -->

    <!-- END INBOX DROPDOWN -->
    <!-- BEGIN TODO DROPDOWN -->

    <!-- END TODO DROPDOWN -->
    <!-- BEGIN USER LOGIN DROPDOWN -->
    <li class="dropdown user">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
			<i class="fa fa-user"></i>
				<span class="username">
					<?=Yii::$app->user->identity['email']?>
				</span>
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="<?=\yii\helpers\Url::to(['/site/reset-password'])?>" data-method="post"><i class="fa fa-pencil"></i> 修改密码</a>
            </li>
            <li>
                <a href="javascript:;" id="trigger_fullscreen"><i class="fa fa-move"></i> 全屏</a>
            </li>
            <li>
                <a href="<?=\yii\helpers\Url::to(['/site/logout'])?>" data-method="post"><i class="fa fa-key"></i> 注销</a>
            </li>
        </ul>
    </li>
    <!-- END USER LOGIN DROPDOWN -->
</ul>
<!-- END TOP NAVIGATION MENU -->