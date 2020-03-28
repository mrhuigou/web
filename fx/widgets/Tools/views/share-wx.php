<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/9
 * Time: 16:55
 */ ?>
<?php
$this->registerJsFile("https://res.wx.qq.com/open/js/jweixin-1.0.0.js",['depends'=>[\fx\assets\AppAsset::className()]]);
$this->beginBlock('JS_INIT')
?>
    /*微信分享*/
    $(".share-guide").click(function(){
    maskdiv($(".share-guide-pop"),"center");
    });
    $(".share-guide-pop").click(function(){
    $(this).fadeOut();
    $(".maskdiv").fadeOut();
    });
    <?php if($data){?>
    var go_url='<?=isset($data['redirect'])?$data['redirect']:''?>';
    var hidden_status='<?=isset($data['hidden_status'])?$data['hidden_status']:''?>';
    var option = {
    title: '<?=$data['title']?>',
    desc: '<?=$data['desc']?>',
    link: '<?=$data['link']?>',
    imgUrl: '<?=$data['image']?>',
    success:function () {
    $.showLoading("正在加载");
    $.post('<?=\yii\helpers\Url::to('/weixin-brage/share',true)?>',{title:'<?=$data['title']?>', desc: '<?=$data['desc']?>',link: '<?=$data['link']?>','trace_code':'<?=$data['trace_code']?>'},function(res){
    $.hideLoading();
    if(res.status){
    $.toast('分享成功!');
    $(".share-guide-pop").trigger("click");
    $(".weui_btn_dialog").trigger("click");
    if(go_url){
    location.href=go_url;
    }
    }else{
    $.toast('分享失败!', 'cancel');
    }
    },'json');
    }
    };
    $.getJSON('/share/sign?url=' + encodeURIComponent(location.href), function (res) {
    wx.config(res);
    wx.ready(function () {
    if(hidden_status){
    wx.hideOptionMenu();
    }else{
    wx.showOptionMenu();
    }
    wx.onMenuShareTimeline(option);
    wx.onMenuShareQQ(option);
    wx.onMenuShareQZone(option);
    wx.onMenuShareWeibo(option);
    wx.onMenuShareAppMessage(option);
    });
    });
<?php } ?>
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_INIT'], \yii\web\View::POS_END);
?>