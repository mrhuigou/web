<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use \yii\helpers\Url;
/* @var $this yii\web\View */
$this->title ='全城互动';
?>
<header class="header w" id="header">
    <div class="tit-left">
        <a href="<?=Url::to(['/site/index'])?>" >
            <span class="iconfont">&#xe63d;</span>
            <span class="f14">首页</span>
        </a>
    </div>
    <h2 class="tc f18">全城互动</h2>
</header>
<section class="veiwport">
    <ul class="filter  redFilter two  mb5 ">
        <li <?=Yii::$app->request->get('run_status')?"":"class=\"cur\""?>><a href="<?=Url::to(['/club-activity/index','run_status'=>0])?>">正在进行中</a></li>
        <li <?=Yii::$app->request->get('run_status')==-1?"class=\"cur\"":""?>><a href="<?=Url::to(['/club-activity/index','run_status'=> -1])?>">往期回顾</a></li>
      </ul>
    <nav class="index-nav clearfix mb5">
        <a href="#" class="pr">
            <i class="iconfont redbg">&#xe605;</i>
            <p>全部</p>
        </a>
        <a href="#">
            <i class="iconfont orgbg">&#xe654;</i>
            <p>组织聚会</p>
        </a>
        <a href="#">
            <i class="iconfont greenbg">&#xe650;</i>
            <p>邀约户外</p>
        </a>
        <a href="#">
            <i class="iconfont bluebg">&#xe653;</i>
            <p>开办沙龙</p>
        </a>
        <a href="#">
            <i class="iconfont bluebg">&#xe64e;</i>
            <p>开展促销</p>
        </a>
        <a href="#">
            <i class="iconfont greenbg">&#xe656;</i>
            <p>举办会议</p>
        </a>
        <a href="#">
            <i class="iconfont orgbg">&#xe651;</i>
            <p>开设课程</p>
        </a>
        <a href="#" class="pr">
            <i class="iconfont redbg">&#xe652;</i>
            <p>发起投票</p>
        </a>
    </nav>
        <?= \yii\widgets\ListView::widget([
            'layout' => "{items}\n{pager}",
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'emptyText'=>'<figure class="info-tips whitebg gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有信息</figcaption></figure>',
            'itemView' => '_item_view',
            'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
                'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
                'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
                'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
                'eventOnRendered' => 'function() { $("img.lazy").scrollLoading();}',
                ]
        ]); ?>
</section>
<?=h5\widgets\MainMenu::widget();?>