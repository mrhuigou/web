<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/20
 * Time: 9:59
 */
$this->title="每日抽奖有惊喜";
?>
<style type="text/css">
    html{
        font-size: 20px;
    }
    /* 对layer弹层的重置 */
    .layermchild{
        background: none!important;
    }
    .layermcont{
        padding: 0!important;
        overflow: hidden;
    }
</style>
<div class="gifts-bg bc w" id="gifts">
    <p class="gifts-t1">每日神密礼盒</p>

    <!-- 礼包图片 -->
    <img src="../assets/images/game/gifts/gifts2.png" class="gifts-img">

    <!-- 左右箭头 -->
    <i class="gifts-arr l iconfont"></i>
    <i class="gifts-arr r iconfont"></i>

    <p class="gifts-t2">磨拳擦掌，不要手滑哟~~</p>

    <!-- 摇一摇按钮 -->
    <a href="javascript:void(0);" class="gifts-shake" id="open-btn">打开礼盒</a>

</div>

<!-- 活动规则 -->
<div class="p15">
    <h2 class="fb pl10 mb2">活动规则</h2>
    <ul class="ml25 pr10 ul ul-decimal gray6">
        <li>每个用户每日仅限一次机会</li>
        <li>最终解释权归每日惠购所有</li>
    </ul>
</div>

<?php $this->beginBlock('JS_END') ?>
$("#open-btn").click(function(){
$.post('<?=\yii\helpers\Url::to('/game/ajax-open')?>',function(data){
if(data.status){
$(document).mask(data.content).click(function(){$(document).unmask()})
}else{
$.alert(data.message);
}
},'json');
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>
