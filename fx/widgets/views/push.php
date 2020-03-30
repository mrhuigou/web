<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/12/26
 * Time: 9:58
 */
\h5\assets\AppAsset::register($this);
?>
<div class="notification pop-tips sticky hide">
    <div id="notification_content"> </div>
    <a class="close" href="javascript:"> X</a>
</div>
<?php
$this->registerJsFile("assets/script/swfobject.js",['condition'=>'IE']);
$this->registerJsFile("assets/script/web_socket.js",['condition'=>'IE']);
$this->registerJs('WEB_SOCKET_SWF_LOCATION = "/assets/script/WebSocketMain.swf";',3);
?>
<?php $this->beginBlock('JS_END') ?>
var url = "ws://192.168.1.224:8282";
if ('WebSocket' in window) {
ws = new WebSocket(url);
} else if ('MozWebSocket' in window) {
ws = new MozWebSocket(url);
} else {
console.log('Your browser Unsupported WebSocket!');
return;
}
// 后端推送来消息时
ws.onmessage = function(msg){
layer.open({
content: msg.data,
});
}
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
