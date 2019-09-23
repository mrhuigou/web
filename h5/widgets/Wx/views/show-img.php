<?php
h5\widgets\Wx\WeixinSDK::widget();
$this->beginBlock('JS_SHOWIMG') ?>
    wx.ready(function () {
        $(".pop-show").live("click",function(){
            var data={current: '',urls: []};
            if($(this).prop("tagName") == "A"){
                data.current=$(this).children('img').attr("src");
                data.urls.push($(this).children('img').attr("src"));
            }else{
                if($(this).attr("data-img") == undefined ){
                    data.current=$(this).attr("src");
                    data.urls.push($(this).attr("src"));
                }else{
                    data.current=$(this).attr("data-img");
                    var list=$(this).parents('ul').find('li img');
                    list.each(function(){
                    data.urls.push($(this).attr("data-img"));
                })
                }
            }
            wx.previewImage(data);
        });
    });
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_SHOWIMG'],\yii\web\View::POS_END);
?>