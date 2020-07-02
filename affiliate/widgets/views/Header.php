<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="header-inner">
        <!-- BEGIN LOGO -->
        <?php  if(Yii::$app->user->getId() == 9){ //地铁?>
            <a class="navbar-brand" href="<?=Yii::$app->homeUrl?>" style="padding: 5px 0;">
                <img src="http://img1.mrhuigou.com/group1/M00/06/EB/wKgB7l777LeAJ1eAAAAPXVHpRm8601.jpg" alt="logo" class="img-responsive" />
            </a>
        <?php }else{?>
            <a class="navbar-brand" href="<?=Yii::$app->homeUrl?>" style="padding: 5px 0;">
                <img src="http://img1.mrhuigou.com/group1/M00/06/E6/wKgB7l7sLsiAZnE_AAAbvrBPGTg113.png" alt="logo" class="img-responsive" />
            </a>
        <?php }?>

<!--        <a class="navbar-brand" href="--><?//=Yii::$app->homeUrl?><!--" style="padding: 5px 0;">-->
<!--            <img src="http://img1.mrhuigou.com/group1/M00/06/E6/wKgB7l7sLsiAZnE_AAAbvrBPGTg113.png" alt="logo" class="img-responsive" />-->
<!--        </a>-->
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <img src="/assets/img/menu-toggler.png" alt=""/>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <?=\affiliate\widgets\TopMenu::widget();?>
    </div>
    <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->