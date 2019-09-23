<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use \yii\helpers\Url;
/* @var $this yii\web\View */
$this->title ='免费试';
?>
<header class="header w" id="header">
    <div class="tit-left">
        <a href="<?=Url::to(['/site/index'])?>" >
        <span class="iconfont">&#xe63d;</span>
        <span class="f14">首页</span>
        </a>
    </div>
    <h2 class="tc f18">免费试</h2>
    <div class="tit-right">
        <a href="javascript:;" class="share-guide">
            <span class="f14">分享</span>
            <span class="iconfont">&#xe644;</span>
        </a>
    </div>
</header>
<section class="veiwport">
    <ul class="filter  redFilter three  ">
        <li <?=Yii::$app->request->get('run_status')?"":"class=\"cur\""?>><a href="<?=Url::to(['/club-try/index','run_status'=>0])?>">正在进行中</a></li>
        <li <?=Yii::$app->request->get('run_status')==1?"class=\"cur\"":""?>><a href="<?=Url::to(['/club-try/index','run_status'=>1])?>">即将开始</a></li>
        <li <?=Yii::$app->request->get('run_status')==-1?"class=\"cur\"":""?>><a href="<?=Url::to(['/club-try/index','run_status'=> -1])?>">往期回顾</a></li>
    </ul>
    <ul class="prom-list">
        <?= \yii\widgets\ListView::widget([
            'layout' => "{items}\n{pager}",
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'list-view'],
            'itemOptions' => ['class' => 'item '],
            'emptyTextOptions' => ['class' => 'empty tc p10 '],
            'itemView' => '_item_view',
            'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
                'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
                'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
                'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
                'eventOnRendered' => 'function() { $("img.lazy").scrollLoading();}',
            ]
        ]);
        ?>
    </ul>
</section>
<?=h5\widgets\MainMenu::widget();?>