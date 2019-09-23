<?php
h5\widgets\Wx\WeixinSDK::widget();
$this->beginBlock('JS_scanQRCode') ?>
    document.querySelector('#scanQRCode').onclick = function () {
    wx.scanQRCode({
    needResult: 1,
    scanType: ["qrCode"],
    desc: '检票功能',
    success: function (res) {
    if(res.errMsg=="scanQRCode:ok"){
    $.post('/club-activity/checked',{'data': res.resultStr,'id':<?=$id?>},function(data){
    var obj=$.parseJSON(data);
    if(obj.result==1){
    swal({
    title: obj.data,
    showCancelButton: true,
    closeOnConfirm: false,
    confirmButtonText: "检票",
    confirmButtonColor: "#4cb301"
    }, function() {
    $.post('/club-activity/checked', {'data': res.resultStr, 'id':<?=$id?>,'act': 'confirm'}, function (data) {
    var obj = $.parseJSON(data);
    if (obj.result == 1) {
    swal(obj.data, "", "success");
    } else {
    swal("失败", obj.data, "error");
    }
    });
    });
    }else{swal("失败", obj.data, "error"); }
    });
    }else{
    swal("失败", JSON.stringify(res), "error");
    }
    }
    });
    };
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_scanQRCode'],\yii\web\View::POS_END);
?>