<?php
$this->title="活动-生活圈".'- 家润慧生活（mrhuigou.com）- 青岛首选综合性同城网购-发现达人体验-分享同城生活';;
?>
<div class="w1100 bc">
    <div class="layout grid-s5m0e5">
        <div class="col-m">
            <div class="main-w">
                <a href="#"><img src="/assets/images/club/feedbg.jpg" alt="活动" width="700" height="230" class="db mb10"></a>
                <div class="graybg w pb5">
                <?php if($category){  ?>
                 <div class="clearfix mb5"><span class="fl fb mlt">分类：</span>
                     <a href="<?=\yii\helpers\Url::current(['filter'=>array_merge($filter,['activity_category_id'=>''])])?>" class="labeltag mlt <?=isset($filter['activity_category_id']) && $filter['activity_category_id']!=''?'':'orglt'?>">全部</a>
                     <?php foreach($category as $value){ ?>
                    <a href="<?=\yii\helpers\Url::current(['filter'=>array_merge($filter,['activity_category_id'=>$value->activity_category_id])])?>" class="labeltag mlt <?=isset($filter['activity_category_id']) && $filter['activity_category_id']==$value->activity_category_id?'orglt':''?>"><?=$value->title?></a>
                    <?php } ?>
                 </div>
                <?php } ?>
                    <div class="clearfix mb5">
                        <span class="fl fb mlt">费用：</span>
                        <a href="<?=\yii\helpers\Url::current(['filter'=>array_merge($filter,['fee'=>''])])?>" class="labeltag mlt <?=isset($filter['fee']) && $filter['fee']!=''?'':'orglt'?>">不限</a>
                        <a href="<?=\yii\helpers\Url::current(['filter'=>array_merge($filter,['fee'=>'1'])])?>" class="labeltag mlt <?=isset($filter['fee']) && $filter['fee']=='1'?'orglt':''?>">收费</a>
                        <a href="<?=\yii\helpers\Url::current(['filter'=>array_merge($filter,['fee'=>'0'])])?>" class="labeltag mlt <?=isset($filter['fee']) && $filter['fee']=='0'?'orglt':''?>">免费</a>
                        <a href="<?=\yii\helpers\Url::current(['filter'=>array_merge($filter,['fee'=>'2'])])?>" class="labeltag mlt <?=isset($filter['fee']) && $filter['fee']=='2'?'orglt':''?>">AA制</a>
                    </div>
                </div>
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
            <div class="graybg tc p10 mb10 none">
                <p class="p10">没有合适我的活动，我要自己发布</p>
                <a href="#" class="btn btn_middle greenBtn">发活动</a>
            </div>
            <?=frontend\modules\club\widgets\RecommendActivity::widget()?>
            <?=frontend\modules\club\widgets\WeekdayActivity::widget()?>
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
