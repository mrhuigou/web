<?php
use backend\components\MenuHelper;
use yii\bootstrap\Nav;
?>
<div class="page-sidebar navbar-collapse collapse">

    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu">
        <li class="sidebar-toggler-wrapper">
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <div class="sidebar-toggler hidden-phone">
            </div>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        </li>
        <li class="sidebar-search-wrapper">
            <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
            <form class="sidebar-search" action="extra_search.html" method="POST">
                <div class="form-container">
                    <div class="input-box">
                        <a href="javascript:;" class="remove"></a>
                        <input type="text" placeholder="Search..."/>
                        <input type="button" class="submit" value=" "/>
                    </div>
                </div>
            </form>
            <!-- END RESPONSIVE QUICK SEARCH FORM -->
        </li>
    </ul>
    <!-- END SIDEBAR MENU -->
    <?=\backend\components\MenuSide::widget([
        'items' => MenuHelper::getAssignedMenu(Yii::$app->user->getId()),
        'options'=>['class'=>'page-sidebar-menu'],
    ]);?>
    <ul class="page-sidebar-menu">
        <li class="">
            <a href="<?= \yii\helpers\Url::to('/site/go-show-stock')?>" target="_blank">
                <span class="title">
                            库存展示
                        </span>
            </a>
        </li>
    </ul>
</div>