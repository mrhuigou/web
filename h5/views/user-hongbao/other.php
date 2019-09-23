<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='我收到的红包';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  pb50">
        <div class="flex-col tc lh37 aui-border whitebg f14 mb5 ">
            <a class="flex-item-6 " href="<?=\yii\helpers\Url::to(['/user-hongbao/index'])?>">
                我分享的
            </a>
            <a class="flex-item-6 aui-border-l aui-border-r redbg white" href="<?=\yii\helpers\Url::to(['/user-hongbao/index','status'=>1])?>">
                帮他人拆的
            </a>
        </div>
    <?= \yii\widgets\ListView::widget([
        'layout' => "{items}\n{pager}",
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'list-view'],
        'itemOptions' => ['class' => 'item '],
        'emptyText'=>'<figure class="info-tips  gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有信息</figcaption></figure>',
        'itemView' => '_item_other_view',
        'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
            'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
            'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
            'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
        ]
    ]);
    ?>
</section>
<!--浮动购物车-->
<?=h5\widgets\MainMenu::widget();?>


