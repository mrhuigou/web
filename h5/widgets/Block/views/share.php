<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/21
 * Time: 10:22
 */
if(strpos(strtolower(Yii::$app->request->getUserAgent()), 'jiaruncustomerapp')){
	$type="app";
}elseif(strpos(strtolower(Yii::$app->request->getUserAgent()), 'micromessenger') ){
	$type="wx";
}else{
    $type="zhqd";
}
?>
<!-- 赚 -->
<?php if($type=='wx' && !Yii::$app->session->get('source_from_agent_wx_xcx')){?>
<a class="zhuan iconfont share-zhuan" href="javascript:void(0);" id="share_icon_btn">&#xe6a5;</a>
<div class="w none share-tip" >
    <div class="tl">
        <h2  style="color: #323232">微信内直接分享</h2>
        <p class="f12">点击微信右上角<span class="white iconfont bg-33">&#xe6aa;</span>，通过【发送给朋友】【分享到朋友圈】推广</p>
    </div>
</div>
	<?php $this->beginBlock('JS_START') ?>
    $("#share_icon_btn").click(function(){
    $.modal({
    title: "立即推广",
    text: $(".share-tip").html(),
    buttons: [
    { text: "关闭", className: "default"}
    ]
    });
    });
	<?php if(Yii::$app->request->get('share_tip') && !Yii::$app->request->get('follower_id')){?>
        $.modal({
        title: "立即推广",
        text: $(".share-tip").html(),
        buttons: [
        { text: "关闭", className: "default"}
        ]
        });
	<?php }?>
	<?php $this->endBlock() ?>
	<?php
	\yii\web\YiiAsset::register($this);
	$this->registerJs($this->blocks['JS_START'],\yii\web\View::POS_END);
	?>
<?php }else{?>
<!--    --><?php //if(!Yii::$app->session->get('source_from_agent_wx_xcx')){?>
<!--        <a class="zhuan iconfont share-guide" href="javascript:void(0);" id="share_icon_btn">&#xe6a5;</a>-->
<!--        --><?php //}?>
<?php } ?>

