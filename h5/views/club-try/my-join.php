<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use \yii\helpers\Url;
/* @var $this yii\web\View */
$this->title ='我的试吃';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18">我的试吃</h2>
</header>
<section class="veiwport">
    <ul class="filter  redFilter  four bdb mt5 clearfix">
        <li class="<?php if(isset($type) && $type=='0'){ echo "cur";}?>"><a href="<?=Url::to(['/club-try/my-join','type'=>0])?>">待开奖</a></li>
        <li class="<?php if(isset($type) && $type=='1'){ echo "cur";}?>"><a href="<?=Url::to(['/club-try/my-join','type'=>1])?>">已中奖</a></li>
        <li class="<?php if(isset($type) && $type=='2'){ echo "cur";}?>"><a href="<?=Url::to(['/club-try/my-join','type'=>2])?>">未中奖</a></li>
        <li ><a href="<?=Url::to(['/club-try/index'],true)?>">试吃中心</a></li>
    </ul>

    <ul class="prom-list ">
        <?= \yii\widgets\ListView::widget([
            'layout' => "{items}\n{pager}",
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'list-view'],
            'itemOptions' => ['class' => 'item '],
            'emptyTextOptions' => ['class' => 'empty tc p10 '],
            'itemView' => 'my-list-view',
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