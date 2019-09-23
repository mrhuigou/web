<div class="side-nav">
    <a href="<?=\yii\helpers\Url::to(['/site/index'],true)?>">购物</a>
    <a href="<?=\yii\helpers\Url::to(['/club/default'],true)?>" class="<?=strpos(Yii::$app->requestedRoute,"default") ?'cur':''?>">活动</a>
    <a href="<?=\yii\helpers\Url::to(['/club/try'],true)?>" class="<?=strpos(Yii::$app->requestedRoute,"try") ?'cur':''?>">免费试</a>
    <a href="<?=\yii\helpers\Url::to(['/club/topic'],true)?>" class="<?=strpos(Yii::$app->requestedRoute,"topic") ?'cur':''?>">我的话题</a>
</div>