<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='我的活动';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18">我的活动</h2>
    <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>
<section class="veiwport">
    <ul class="filter blueFilter two mb10">
        <li <?=$type=='joined'?"class=\"cur\"":""?>><a href="<?=\yii\helpers\Url::to(['/club-activity/my','type'=>'joined'])?>">我参与的</a></li>
        <li <?=$type=='created'?"class=\"cur\"":""?>><a href="<?=\yii\helpers\Url::to(['/club-activity/my','type'=>'created'])?>">我创建的</a></li>
      </ul>
        <?= \yii\widgets\ListView::widget([
            'layout' => "{items}\n{pager}",
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'list-view'],
            'itemOptions' => ['class' => 'item '],
            'emptyTextOptions' => ['class' => 'empty tc p10 '],
            'itemView' => 'my-created-list',
            'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
                'triggerTemplate'=>'<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">{text}</button></div>',
                'noneLeftTemplate'=>'<div class="ias-noneleft tc p10">{text}</div>',
            ]
        ]);
        ?>
</section>
