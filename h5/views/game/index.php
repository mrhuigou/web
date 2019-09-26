<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/11/7
 * Time: 16:30
 */
use yii\helpers\Html;
$this->title = $lottery->title;
?>
<style>
    body{
        background-color: #97040e;
    }
    .weui_dialog{
        padding: 0 0;
    }
    .weui_dialog_bd{
        padding: 0px 0px;
    }
    .weui_dialog_ft{
        margin-top: 0px;
    }
</style>
<header class="fx-top bs-bottom whitebg lh44">
    <div class="flex-col tc">
        <a class="flex-item-2" href="/">
            <i class="aui-icon aui-icon-home green f28"></i>
        </a>
        <div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
        </div>
<!--        <a class="flex-item-2" href="--><?//=\yii\helpers\Url::to(['/user/index'])?><!--">-->
<!--            <i class="iconfont green f28">&#xe603;</i>-->
<!--        </a>-->
        <a class="flex-item-2 share-guide" href="javascript:;" >
            <i class="iconfont green f28">&#xe644;</i>
        </a>
    </div>
</header>
<div class="w mt50"></div>
<img src="/assets/images/game/img/banner_1.jpg" class="w">
<!--    <p class="tc fb lh150 white" >活动时间：9月14日~9月15日</p>-->
<!--    <p class="tc fb lh150 white" >抽奖须知：现金券3日内有效。</p>-->
<div class="lottery-wrap bc" style=" background-color: #97040e;">
    <div class="pw80 bc">
        <div id="lottery" class="clearfix">
            <div class="lottery-unit lottery-unit-0">
                <img src="<?=\common\component\image\Image::resize($model[0]->image)?>" class="w db img-0">
            </div>
            <div class="lottery-unit lottery-unit-1">
                <img src="<?=\common\component\image\Image::resize($model[1]->image)?>" class="w db img-1">
            </div>
            <div class="lottery-unit lottery-unit-2">
                <img src="<?=\common\component\image\Image::resize($model[2]->image)?>" class="w db img-2">
            </div>
            <div class="lottery-unit lottery-unit-7">
                <img src="<?=\common\component\image\Image::resize($model[7]->image)?>" class="w db img-7">
            </div>
            <div class="lottery-btn">
                <a href="javascript:void(0);"><img src="/assets/images/game-btn.jpg" alt="抽奖按钮" class="w db"></a>
            </div>
            <div class="lottery-unit lottery-unit-3">
                <img src="<?=\common\component\image\Image::resize($model[3]->image)?>" class="w db img-3">
            </div>
            <div class="lottery-unit lottery-unit-6">
                <img src="<?=\common\component\image\Image::resize($model[6]->image)?>" class="w db img-6">
            </div>
            <div class="lottery-unit lottery-unit-5">
                <img src="<?=\common\component\image\Image::resize($model[5]->image)?>" class="w db img-5">
            </div>
            <div class="lottery-unit lottery-unit-4">
                <img src="<?=\common\component\image\Image::resize($model[4]->image)?>" class="w db img-4">
            </div>
        </div>
    </div>
</div>
<div id="result" class="none">
    <div class="red tc bc w ">
     <p class="p5  tc f20 fb" id="result_name"></p>
     <p class="mt5 mb10  tc f14 " id="result_description"></p>
      <a class="tc" href="/user-coupon/index">
<!--          <img src="/images/618.jpg" class="w">-->
      </a>
    </div>
</div>
<?php $this->beginBlock('J_Reviews') ?>
//抽奖
var lottery={
    index:-1,	//当前转动到哪个位置
    count:0,	//总共有多少个位置
    timer:0,	//setTimeout的ID，用clearTimeout清除
    speed:200,	//初始转动速度
    times:0,	//转动次数
    cycle:50,	//转动基本次数：即至少需要转动多少次再进入抽奖环节
    prize:-1,	//中奖位置
    init:function(id){
        if ($("#"+id).find(".lottery-unit").length>0) {
            $lottery = $("#"+id);
            $units = $lottery.find(".lottery-unit");
            this.obj = $lottery;
            this.count = $units.length;
            $lottery.find(".lottery-unit-"+this.index).addClass("active");
        };
    },
    roll:function(){
        var index = this.index;
        var count = this.count;
        var lottery = this.obj;
        $(lottery).find(".lottery-unit-"+index).removeClass("active");
        index += 1;
        if (index>count-1) {
            index = 0;
        };
        $(lottery).find(".lottery-unit-"+index).addClass("active");
        this.index=index;
        return false;
    },
    stop:function(index){
        this.prize=index;
        return false;
    }
};

function roll(){
    lottery.times += 1;
    lottery.roll();
    if (lottery.times > lottery.cycle+10 && lottery.prize==lottery.index) {
        clearTimeout(lottery.timer);
        $.alert($("#result").html());
        $.modal({
            title: "恭喜您获得",
            text: $("#result").html(),
            buttons: [
                { text: "邀请好友来抽奖", onClick: function(){ $(".share-guide").trigger("click");; } },
            ]
        });
        lottery.prize=-1;
        lottery.times=0;
        click=false;
    }else{
        if (lottery.times < lottery.cycle) {
            lottery.speed -= 10;
        }else{
            if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
                lottery.speed += 110;
            }else{
                lottery.speed += 20;
            }
        }
        if (lottery.speed<40) {
            lottery.speed=40;
        };
        lottery.timer = setTimeout(roll,lottery.speed);
    }
    return false;
}
var click=false;
lottery.init('lottery');
$("#lottery a").click(function(){
    if (click) {
        return false;
    }else{
        $.showLoading("正在加载");
        $.post('/game/apply-lottery',{},function(data){
            $.hideLoading();
            if(data.status){
                lottery.speed=100;
                lottery.prize = data.prize;
                $("#result_name").html(data.title);
                $("#result_description").html(data.description);
                roll();
                click=true;
                setTimeout(function () {
                    $.get("/game/send-notice?customer_coupon_id="+data.customer_coupon_id);
                }, 4000);
            }else{
                $.alert(data.message);
            }
        },'json');
        return false;
    }
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['J_Reviews'],\yii\web\View::POS_END);
?>
<?php
if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $data = [
        'title' => $lottery->title.'！！！',
        'desc' => "物美价廉，当日订单，当日送达。",
        'link' => \yii\helpers\Url::to(['/game/index'],true),
        'image' => 'https://m.mrhuigou.com/images/gift-icon.jpg'
    ];
}else{
    $data = [
        'title' => $lottery->title.' ！！！',
        'desc' => "每日惠购，物美价廉，当日订单，当日送达。",
        'link' => \yii\helpers\Url::to(['/game/index'],true),
        'image' => 'https://m.mrhuigou.com/images/gift-icon.jpg'
    ];
}
?>
<?=\h5\widgets\Tools\Share::widget([
	'data'=>$data
])?>