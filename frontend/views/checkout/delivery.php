<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/30
 * Time: 10:41
 */
$this->context->layout = "main-iframe";
?>
<style type="text/css">
    #allmap{width:100%;height:300px;}
</style>
<div class="delivery_station_items  w" >
    <div class="row ">
    <div  class="bs-col-5 ">
        <div class="p5 whitebg " style="height: 280px;overflow: auto;">
            <input type="hidden" name="station_id" id="station_id" value="<?=$station_id?>">
            <?php foreach($model->station as $key=>$value){?>
            <label class="selfpick-item clearfix <?=($value->id==$station_id)?'cur':''?> " data-lng="<?=$value->latitude?>" data-lat="<?=$value->longitude?>" id="<?=$value->id?>">
                <div>
                    <h2 class="fb"><em class="numicon white tc mr5"><?=$key+1?></em><?=$value->name?></h2>
                    <p class="gray9 mt5 mb5">
                        <span class="gray3">地址：</span>
                        <?=$value->address?>
                    </p>
                    <p class="gray9">
                        <span class="gray3">电话：</span>
                        <?=$value->telephone?>
                    </p>
                </div>
                <i class="iconfont">&#xe627;</i>
            </label>
            <?php } ?>
        </div>
    </div>
    <div class="bs-col-7">
        <div id="allmap"></div>
    </div>
    </div>
    <div class="line_dash mt5"></div>
    <div class="clearfix">
        <div class="p5 fl">
            <label>收货人姓名</label>
            <input type="text" class="input minput w220" name="username" id="username" maxlength="10" value="<?=$username?>">
        </div>
        <div class="p5 fl">
            <label>联系电话</label>
            <input type="text" class="input minput w220" name="telephone" id="telephone" maxlength="11" value="<?=$telephone?>">
        </div>
    </div>
    <div class="mt5 pl10 org">
        <div class="checkbox">
            <label for="form-agree" class="vm">
             <input type="checkbox" checked="checked" value="1" name="agree" id="form-agree" class="vm">
                同意遵守自提点用户协议
            </label>
        </div>
    </div>
    <div class="p10">
        <button type="button" class="btn mbtn greenbtn w" id="saveStationBtn">确定</button>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
var ptsObj =<?=$data?>;
var store_id='<?=$model->store_id?>';
var map = new BMap.Map("allmap");
map.centerAndZoom(new BMap.Point(ptsObj.default.lng,ptsObj.default.lat), 15);
init_stations(ptsObj.data);
function init_stations(data){
$.each(data,function(n,value){
setMarks(n+1,new BMap.Point(value.lng,value.lat));
});
}
function setMarks(marks,lal){
mark = new BMap.Marker(lal);
var label = new BMap.Label(marks,  {offset : new BMap.Size(0, 0) });  // 创建文本标注对象
label.setStyle({ //设置样式
fontSize : "12px",
height : "20px",
lineHeight : "20px",
background: "none",
border: "none",
padding:"1px 6px",
color:"#fff"
});
mark.setLabel(label);
map.addOverlay(mark);
}
$("body").on("click",".selfpick-item",function(){
$(this).parents(".delivery_station_items").find('.selfpick-item').removeClass('cur');
$(this).addClass('cur');
$("#station_id").val($(this).attr('id'));
var lat=$(this).attr('data-lat');
var lng=$(this).attr('data-lng');
map.centerAndZoom(new BMap.Point(lat,lng), 15);
});
$("#saveStationBtn").on("click",function(){
if($("#username").val().length<2 || $("#username").val().length>10){
parent.layer.msg('请填写收货人姓名2到10个字)');
return;
}
if($("#telephone").val().length!=11){
parent.layer.msg('联系电话为11位');
return;
}
if(!$("input[type='checkbox']").is(':checked')){
parent.layer.msg('必须同意自提点协议');
return;
}
var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
var delivery_type_items=parent.$("#store_contain_"+store_id).find(".delivery_type_items");
delivery_type_items.find('a').removeClass('cur');
delivery_type_items.find('.self-delivery').addClass('cur');
parent.$("#store_contain_"+store_id).find('.delivery_station_id').val($("#station_id").val());
parent.$("#store_contain_"+store_id).find('.delivery_type').val('self-delivery');
parent.$("#store_contain_"+store_id).find('.delivery_station_username').val($("#username").val());
parent.$("#store_contain_"+store_id).find('.delivery_station_telephone').val($("#telephone").val());
parent.AsyUpdateShopTotals(store_id);
parent.layer.close(index);
});
<?php $this->endBlock() ?>
<?php
$this->registerJsFile("http://api.map.baidu.com/getscript?v=2.0&ak=qrDz4DGnKDfg0WtdDkOYn0Op",['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js",['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js",['position' => \yii\web\View::POS_HEAD]);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
