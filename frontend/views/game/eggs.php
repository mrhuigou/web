
<style>
    .eggbg{
        background: url("/assets/images/game/egg/bg.jpg") center repeat ;
        height: auto;
    }
    #egg .egg{
        background: url("/assets/images/game/egg/egg_1.png") center no-repeat;
        background-size: contain;
        height: 150px;
        margin-bottom: 20px;
    }
    #egg .cur{
        background: url("/assets/images/game/egg/egg_2.png") center no-repeat;
        background-size: contain;

    }
    #egg .cur span{background:url("/assets/images/game/egg/img-6.png") center no-repeat;
        background-size: contain;
        display: block;
        background-position: right center;
    }
    #egg .cur sup{background:url("/assets/images/game/egg/img-4.png") center no-repeat;
        background-size: contain;
        display: block;
    }
</style>
<div id="main" class="w bc eggbg pb30">
    <div id="egg" class="pb20  ">
        <div class="w  tc"><img src="/assets/images/game/egg/ad.jpg"></div>
        <div  class="w600 bc  ">
            <div class="row  p10" >
                <div class="egglist col-3 egg cp"><sup><span></span></sup></div>
                <div class="egglist col-3 egg cp"><sup><span></span></sup></div>
                <div class="egglist col-3 egg cp"><sup><span></span></sup></div>
                <div class="egglist col-3 egg cp"><sup><span></span></sup></div>
                <div class="egglist col-3 egg cp"><sup><span></span></sup></div>
                <div class="egglist col-3 egg cp"><sup><span></span></sup></div>
                <div class="egglist col-3 egg cp"><sup><span></span></sup></div>
                <div class="egglist col-3 egg cp"><sup><span></span></sup></div>
                <div class="egglist col-3 egg cp"><sup><span></span></sup></div>
            </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('J_Reviews') ?>
$(".egglist").click(function(){
var obj= $(this);
$.getJSON("<?php echo \yii\helpers\Url::to(['game/play-eggs'])?>",function(res){
if(res.status=='success'){
obj.siblings().removeClass('cur');
obj.addClass('cur');
layer.open({
type: 1,
title: [
'游戏结果',
'background-color:#fe4830; color:#fff;'
],
area: ['420px', '240px'], //宽高
content: '<div class="tc mt20"> <i class="iconfont red " style="font-size: 28px">&#xe628;</i><p class="mt10 mb50 ">'+res.message+'</p><a href="/coupon/index" class="btn mbtn yellowbtn mr10 w90">查看</a></div>',

});
}else{
layer.open({
title: [
'提示',
'background-color:#fe4830; color:#fff;'
],
type: 1,
area: ['420px', '240px'], //宽高
content: '<div class="tc red" style="margin-top: 40px;"> <p>您已经木有机会了，请购物后再来哟~~ </p><a href="<?php echo \yii\helpers\Url::to(['/site/index'])?>" class="mt20 btn mbtn yellowbtn mr10 w90">去购物</a></div>',
});
}
});
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['J_Reviews'],\yii\web\View::POS_END);
?>