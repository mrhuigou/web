<?php
h5\widgets\Wx\WeixinSDK::widget();
$this->beginBlock('JS_IMAGE') ?>
wx.ready(function () {
document.querySelector('#chooseImage').onclick = function () {
    wx.chooseImage({
    success: function (res) {
     if(res.localIds.length!==1){
        alert("只能上传一张图片");
    }else{
        wx.uploadImage({
        localId: res.localIds[0],
        success: function (res) {
            $.get('<?=\yii\helpers\Url::to(['/club-comment/download'])?>',{media_id:res.serverId},function(data){
                $("#activityform-image").val(data);
                $(".upload_pic").hide();
                $("#activity_theme_pic").attr("src",data).show();
            });
        },
        fail: function (res) {
        alert(JSON.stringify(res));
        }
        });
    }
    }});
};
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_IMAGE'],\yii\web\View::POS_READY);
?>