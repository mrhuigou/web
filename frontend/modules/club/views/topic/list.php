<?php
if($model->title){
    $this->title = $model->title."-话题列表-全城互动".'- 每日惠购（mrhuigou.com）- 青岛首选综合性同城网购-发现达人体验-分享同城生活';
}else{
    $this->title="话题列表-生活圈".'- 每日惠购（mrhuigou.com）- 青岛首选综合性同城网购-发现达人体验-分享同城生活';
}

?>
<div class="w1100 bc">
    <div class="layout grid-s5m0e5">
        <div class="col-m">
            <div class="main-w">
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
        <div class="col-e">
            <div class="graybg tc p10 mb10">
               <!-- <p class="p10">没有合适我的活动，我要自己发布</p>-->
                <a href="<?php echo \yii\helpers\Url::to(['/club/topic/cancel','sub_id'=> Yii::$app->request->get("sub_id")])?>" class="btn btn_middle greenBtn">取消关注</a>
            </div>
            <?=frontend\modules\club\widgets\RecommendActivity::widget()?>
            <?=frontend\modules\club\widgets\HotExp::widget()?>
        </div>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
$("body").on('mouseover','.trans',function(){
var obj=$(this).next();
var url = obj.attr("id");
var qr = obj.find(".img");
var i = qr.children("canvas").length;
if(i==0){
$(qr).qrcode({
text:url,  //设置二维码内容
render:'canvas',//设置渲染方式
width:75,     //设置宽度
height:75     //设置高度
});
}
obj.show();
});
$("body").on('mouseout','.trans',function(){
var obj=$(this).next();
obj.hide();
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
