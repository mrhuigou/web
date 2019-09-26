<?php $this->title = '免费试吃'.'-生活圈- 每日惠购（mrhuigou.com）- 青岛首选综合性同城网购-发现达人体验-分享同城生活';?>
<div class="w1100 bc">
    <div class="layout grid-s5m0">
        <div class="col-m">
            <div class="main-w">
                <ul class="filter three mt10 mb10">
                    <li><a class="<?=$status==1?"green":""?> f16" href="<?=\yii\helpers\Url::to(['/club/try/index','status'=>1])?>">正在进行</a></li>
                    <li><a class="<?=$status==2?"green":""?> ml5 mr5" href="<?=\yii\helpers\Url::to(['/club/try/index','status'=>2])?>">即将开始</a></li>
                    <li><a class="<?=$status==3?"green":""?> " href="<?=\yii\helpers\Url::to(['/club/try/index','status'=>3])?>">往期回顾</a></li>
                </ul>
                <?= \yii\widgets\ListView::widget([
                    'layout' => "{items}\n{pager}",
                    'dataProvider' => $dataProvider,
                    'itemOptions' => ['class' => 'item'],
                    'emptyTextOptions' => ['class' => 'empty tc p10 '],
                    'itemView' => '_item_view',
                    'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
                        'triggerTemplate'=>'<div class="w tc mt10 "><button class="ias-trigger appbtn p10 w  ">{text}</button></div>',
                        'noneLeftTemplate'=>'<div class="ias-noneleft tc p10">{text}</div>',
                        'eventOnRendered'=>'function() { $("img.lazy").lazyload({effect:"fadeIn"});}',
                    ]
                ]); ?>
            </div>
        </div>

        <div class="col-s">
            <?=frontend\modules\club\widgets\Menu::widget()?>
        </div>
    </div>
</div>
