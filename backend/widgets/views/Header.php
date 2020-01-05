<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-static-top">
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="header-inner">
        <!-- BEGIN LOGO -->
        <a class="navbar-brand" href="<?=Yii::$app->homeUrl?>" style="padding: 5px 0;">
            <img src="/assets/img/logo_mrhg.jpg" alt="logo" class="img-responsive" />
        </a>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <img src="/assets/img/menu-toggler.png" alt=""/>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <?=\backend\widgets\TopMenu::widget();?>
    </div>
    <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->