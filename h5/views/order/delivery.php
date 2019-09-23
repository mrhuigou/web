<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/11
 * Time: 16:54
 */
$this->title = '订单物流服务评价';
?>
<?php $this->registerCssFile('/assets/script/raty/jquery.raty.css');?>
<?= h5\widgets\Header::widget(['title' => $this->title]) ?>
<section class="">
    <div class="p10 bg-wh">
        <p class="tc">
            <i class="iconfont blue f70">&#xe6a3;</i>
        </p>
        <div class="tit-- gray6 f12">为本次物流服务打分</div>
        <div class="tc pt10">
            <div class="bd p15 mr50 ml50 bg-fb star-scoring-1"></div>
            <div class="star-hint-1"></div>
        </div>

        <hr class="mt20 mb20">

        <p class="tc mb15">给物流人员点个赞吧</p>

        <div class="mb10" id="tags">
            <a href="javascript:void(0)" class="btn btn-s btn-bd-gray btn-pill mb5 btn-blue">准时送达</a>
            <a href="javascript:void(0)" class="btn btn-s btn-bd-gray btn-pill mb5">送货上门</a>
            <a href="javascript:void(0)" class="btn btn-s btn-bd-gray btn-pill mb5">态度友好</a>
            <a href="javascript:void(0)" class="btn btn-s btn-bd-gray btn-pill mb5">工装整洁</a>
            <a href="javascript:void(0)" class="btn btn-s btn-bd-gray btn-pill mb5">礼貌用语</a>
            <a href="javascript:void(0)" class="btn btn-s btn-bd-gray btn-pill mb5">包装完整</a>
        </div>

        <textarea class="textarea bd-blue- w mb5" rows="3" placeholder="您的评价" id="comment"></textarea>

        <a href="javascript:;" class="btn btn-l btn-green w mb50" id="submit_comment">匿名评价</a>
    </div>
</section>
<?php $this->beginBlock('JS_END') ?>
var score=0;
$("#submit_comment").on('click',function(){
var comment=$("#comment").val();
$.showLoading("正在提交");
var tags=[];
$("#tags a").each(function(){
if($(this).hasClass("btn-blue")){
tags.push($(this).text());
}
});
$.post('<?=\yii\helpers\Url::to(['/order/delivery-submit','order_no'=>$order_model->order_no],true)?>',{'score':score,'tags':tags,'comment':comment},function(res){
$.hideLoading();
if(res.status){
$.toast('评论成功!');
setTimeout("location.reload();",1000);
}else{
$.alert(res.message);
}
},'json');
});
//星级评论
$('.star-scoring-1').raty({
starType: 'i',
hints   : ['不满意', '有待提高', '大体可以', '满意', '非常满意'],
target: '.star-hint-1',
click: function(s, evt) {
score=s;
}
});
$("#tags a").on('click',function(){
if($(this).hasClass("btn-blue")){
$(this).removeClass("btn-blue");
}else{
$(this).addClass("btn-blue");
}
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJsFile("/assets/script/raty/jquery.raty.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
