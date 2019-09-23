<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/22
 * Time: 15:28
 */
$this->title="幸运大抽奖";
?>
<style type="text/css">
	* { margin: 0; padding: 0;}
	body { font-family: "Microsoft Yahei"; background-image: url("/assets/images/lottery/bg.jpg")!important;background-position: center;background-size: contain;}
	.rotary { position: relative;  width: 30rem; height: 30rem; margin: 10px 10px; background-image: url(/assets/images/lottery/gg.png) ;background-repeat:no-repeat;background-size:contain;}
	.hand { position: absolute; left: 9.6rem; top:  9.6rem; width: 10.8rem; height: 10.8rem; cursor: pointer;}
	.bg{background-image: url("/assets/images/lottery/bg.jpg")!important;background-size: contain;margin:0 auto;background-color: #b90d0d;    background-repeat: no-repeat;}
</style>
<div class="bg">
<div class="clearfix">
    <img src="/assets/images/lottery/banner2.png" class="w">
	<div class="rotary bc">
		<img class="hand" src="/assets/images/lottery/z.png" alt="">
	</div>
</div>
<div class="br5 opc-f p10 m10">
    <!-- 看看大家 -->
        <div class="tit-- mt10">中奖结果</div>
        <div id="user_list">
            <?php if($history){ ?>
                <?php foreach ($history as $value){?>
                    <div class="flex-col activity-1-list">
                        <div class="flex-item-2 tc">
                            <img src="<?=\common\component\image\Image::resize($value->customer->photo,100,100)?>" alt="头像" width="47" height="47" class="img-circle">
                        </div>
                        <div class="flex-item-6 pl10">
                            <p class="pt5"><?=$value->customer->nickname?></p>
                            <p class="gray6 f12 pt2"><?=date('m/d H:i:s',$value->creat_at)?></p>
                        </div>
                        <div class="flex-item-4 tr">
	                        <?=$value->prize->title?>
                        </div>
                    </div>
                <?php } ?>
            <?php }else{ ?>
            <p class="tc lh200" id="result_prize">暂时没有中奖信息</p>
            <?php } ?>
        </div>
    <!-- 活动规则 -->
    <div class="tit-- mt15 mb10">活动规则</div>
    <ul class="ul ul-decimal ml25 f14">
        <li>每个用户只能抽一次哟</li>
        <li>其他未尽事宜，请咨询现场工作人员</li>
    </ul>
</div>
</div>
<script id="tpl" type="text/html">
    <% for(var i=from; i< to; i++) {%>
    <div class="flex-col activity-1-list">
        <div class="flex-item-2 tc">
            <img src="<%:=list[i].photo%>" alt="头像" width="47" height="47" class="img-circle">
        </div>
        <div class="flex-item-6 pl10">
            <p class="pt5"><%:=list[i].nickname%></p>
            <p class="gray6 f12 pt2"><%:=list[i].datetime%></p>
        </div>
        <div class="flex-item-4 tr">
            <%:=list[i].des%>
        </div>
    </div>
    <% } %>
</script>
<?php $this->beginBlock('J_Reviews') ?>
var $hand = $('.hand');
$hand.click(function(){
$.showLoading();
$.post('/lottery/apply',{v:new Date().getTime(),'id':'<?=$id?>'},function(res){
$.hideLoading();
if(res.status){
rotateFunc(res.angle,res.message);
}else{
$.alert(res.message);
}
});
});
var rotateFunc = function(angle,text){
$hand.stopRotate();
$hand.rotate({
angle: 0,
duration: 5000,
animateTo: angle + 2880,
callback: function(){
$.alert(text);
loading();
}
});
};
var lastID='<?=$last_id?>';
var tpl = $('#tpl').html();
function loading(){
$.post('<?=\yii\helpers\Url::to(['/lottery/result'])?>',{'last_id':lastID,'lottery_id':'<?=$id?>'},function(res){
if(res){
if(res.data.length>0){
$("#result_prize").remove();
for (var i = 0; i < res.data.length; i++) {
lastID=res.data[i].id;
}
var html= template(tpl, {list:res.data,from:0,to:res.data.length});
$("#user_list").append(html);
}
}
},'json');
}
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJsFile("/assets/script/jquery.rotate.min.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJs($this->blocks['J_Reviews'],\yii\web\View::POS_END);
?>
