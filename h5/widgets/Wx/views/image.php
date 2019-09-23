<p class="mt10">
    <a id="chooseAddress" href="javascript:;">
    <i class="iconfont vm red">&#xe622;</i><span class="vm" id="address">未知</span>
    </a>
</p>
<div class="whitebg bd p10 mt15">
    <p class="mb10"><i class="iconfont f18 mr5">&#xe646;</i>添加图片，会让分享更有吸引力</p>
    <div class="clearfix">
        <!--添加按钮-->
					<span class="unload-pic-addbtn" id="chooseImage">
						<em class="iconfont">&#xe60c;</em>
					</span>
    </div>
</div>
<?php
h5\widgets\Wx\WeixinSDK::widget();
$this->beginBlock('JS_IMAGE') ?>
wx.ready(function () {
wx.hideOptionMenu();
$("#chooseAddress").live('click',function(){
$("#address").html("定位中...");
wx.getLocation({
type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
success: function (res) {
$.get('<?=\yii\helpers\Url::to(['/club-comment/get-address'])?>',{latitude:res.latitude,longitude:res.longitude},function(data){
$("#clubcommentform-address").val(data);
$("#address").html(data);
});
}
});
});
wx.getLocation({
type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
success: function (res) {
$.get('<?=\yii\helpers\Url::to(['/club-comment/get-address'])?>',{latitude:res.latitude,longitude:res.longitude},function(data){
$("#clubcommentform-address").val(data);
$("#address").html(data);
});
}
});
var images = {localId: [],serverId: [],data:[]};
var image_total=0;
$('#chooseImage').live('click',function(){
   //选拍图片
    wx.chooseImage({
    success: function (res) {
    images.localId = res.localIds;
    var i = 0, length =images.localId.length;
    image_total=image_total+length;
    images.serverId = [];
    function upload() {
    wx.uploadImage({localId: images.localId[i],
        success: function (res) {
        i++;
        images.serverId.push(res.serverId);
        $.get('<?=\yii\helpers\Url::to(['/club-comment/download'])?>',{media_id:res.serverId},function(data){
        images.data.push(data);
        $("#clubcommentform-images").val(images.data.join(","));
        html='<span class="unload-pic md5 del_pic">';
        html+='<img src="'+ data +'" alt="tu" width="60" height="60" class="db">';
        html+='</span>';
        $("#chooseImage").before(html);
        if(image_total==3){
             $("#chooseImage").hide();
        }
        });
        if (i < length) { upload(); }
        },
        fail: function (res) {
        alert(JSON.stringify(res));
        }
    });
    }
    if(image_total<4){
    upload();
    }else{
    image_total=image_total-length;
    alert("图片最多上传3张");
    }
    }
    });
});

$(".del_pic").live("click",function(){
    var d=$(this).find('img').attr('src');
    images.data.splice($.inArray(d,images.data),1);
    $("#clubcommentform-images").val(images.data.join(","));
    image_total=image_total-1;
    $(this).remove();
    if(image_total<3){
    $("#chooseImage").show();
    }
});


});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_IMAGE'],\yii\web\View::POS_READY);
?>