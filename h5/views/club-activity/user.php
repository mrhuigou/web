<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='用户列表-全城活动';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18">用户列表</h2>
</header>
<section class="veiwport">
    <div class="p10 whitebg" style="min-height: 420px;">
        <?= \yii\widgets\ListView::widget([
            'layout' => "{items}\n{pager}",
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'list-view'],
            'itemOptions' => ['class' => 'item '],
            'emptyTextOptions' => ['class' => 'empty tc p10 '],
            'itemView' => 'user-list-view',
            'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
                'triggerTemplate'=>'<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">{text}</button></div>',
                'noneLeftTemplate'=>'<div class="ias-noneleft tc p10">{text}</div>',
            ]
        ]);
        ?>
    </div>
</section>
<?php
h5\widgets\Wx\ShowImg::widget();
?>