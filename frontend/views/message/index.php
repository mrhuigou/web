<?php
use yii\widgets\Breadcrumbs;
$this->title = '我的消息';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="graybg2 pb10" style="min-width:1100px;">
    <div class="w1100 bc ">
        <!--面包屑导航-->
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'tag' => 'p',
            'options' => ['class' => 'gray6 pb5 pt5'],
            'itemTemplate' => '<a class="f14">{link}</a> > ',
            'activeItemTemplate' => '<a class="f14">{link}</a>',
        ]) ?>
        <div class="bc  clearfix simsun">
            <div class="fl w100 mr10 menu-tree">
                <?= frontend\widgets\UserSiderbar::widget() ?>
            </div>
            <div class="fl w990 ">
        <div class="user_center clearfix whitebg">

            <div class="">
                <p class="whitebg p15 bdb">
                    消息总计：<span class="org mr30">未读：(<?=$count_unread?>)</span> <span class="gray9">已读(<?=$count_read?>)</span>
                </p>

                <div class="pl20 pr20 pb50 gray6">
                    <?= \yii\widgets\ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemOptions' => ['class' => 'item'],
                        'emptyTextOptions' => ['class' => 'empty tc p10 '],
                        'itemView' => '_list_view',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>



        function setRead(url,message_id){
            $.ajax({
                type: "POST",
                url: "<?php echo \yii\helpers\Url::to('message/set-read',true)?>",
                data: "message_id="+message_id,
                success: function(msg){
                    if(url){
                        window.location.href = url;
                    }
                    if(msg.status == 'false'){
                        alert(msg.msg);
                    }

                }
            });

        }
    <?php $this->endBlock() ?>
    <?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
    ?>
