<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/9/5
 * Time: 16:58
 */
use yii\helpers\Html;
$this->title='管理活动';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18">管理活动</h2>
    <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>
<section class="veiwport">
    <ul class="filter blueFilter four  mb10 ">
        <li class="<?=$view?'':'cur'?>"><a href="<?=\yii\helpers\Url::to(['/club-activity/manage','id'=>Yii::$app->request->get('id')])?>">管理活动</a></li>
        <li class="<?=$view=='enroll'?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['/club-activity/manage','id'=>Yii::$app->request->get('id'),'view'=>'enroll'])?>">报名</a></li>
        <li class="<?=$view=='comment'?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['/club-activity/manage','id'=>Yii::$app->request->get('id'),'view'=>'comment'])?>">评论</a></li>
        <li class="<?=$view=='like'?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['/club-activity/manage','id'=>Yii::$app->request->get('id'),'view'=>'like'])?>">赞</a></li>
    </ul>
    <?= \yii\widgets\ListView::widget([
        'layout' => "{summary}\n{items}\n{pager}",
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'list-view'],
        'itemOptions' => ['class' => 'item '],
        'emptyTextOptions' => ['class' => 'empty tc p10 '],
        'itemView' => 'manage-like-list',
        'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
            'triggerTemplate'=>'<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">{text}</button></div>',
            'noneLeftTemplate'=>'<div class="ias-noneleft tc p10">{text}</div>',
        ]
    ]);
    ?>
</section>