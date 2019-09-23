<script>


<?php
$this->beginBlock('JS_INIT')
?>
$("#share-btn").click(function(){
    api.download({
        url: '<?=$data['image']?>',
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
                            <?php if(isset($data['redirect'])){?>
                            location.href='<?=$data['redirect']?>';
                            <?php }else{ ?>
                            layer.open({
                                content: '分享成功！',
                                skin: 'msg',
                                time: 2
                            });
                            <?php } ?>
                        }

                    });
                } else {
                    alert('当前设备未安装微信客户端');
                }
            });
        }
    });
});
<?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS_INIT'],\yii\web\View::POS_END);
?>
