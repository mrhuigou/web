<?php
$this->beginBlock('JS_INIT')
?>
$(".share-guide").click(function(){
var go_url='<?=isset($data['redirect'])?$data['redirect']:''?>';
api.download({
url: '<?=isset($data['image'])?$data['image']:"https://m.mrhuigou.com/assets/images/logo_300x300.png"?>',
report: true,
cache: true,
allowResume: true
}, function(ret, err) {
if (ret.state == 1) {
realPath = ret.savePath;
var wx = api.require('wx');
wx.isInstalled(function(ret, err) {
if (ret.installed) {
wx.shareWebpage({
apiKey: 'wx12622d0b735c449c',
scene: 'session',
title: '<?=$data['title']?>',
description: '<?=$data['desc']?>',
thumb: realPath,
contentUrl: '<?=$data['link']?>'
}, function(ret, err) {
if (ret.status) {
$.showLoading("正在加载");
$.post('<?=\yii\helpers\Url::to('/weixin-brage/share',true)?>',{title:'<?=$data['title']?>', desc: '<?=$data['desc']?>',link: '<?=$data['link']?>','trace_code':'<?=$data['trace_code']?>'},function(res){
$.hideLoading();
if(res.status){
$.toast('分享成功!');
if(go_url){
location.href=go_url;
}
}else{
$.toast('分享失败!', 'cancel');
}
},'json');
}
});
} else {
$.alert('当前设备未安装微信客户端!');
}
});
}
});
});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_INIT'],\yii\web\View::POS_END);
?>
