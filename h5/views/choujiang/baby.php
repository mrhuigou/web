<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/5/18
 * Time: 9:11
 */
$this->title = '母婴专场抽奖活动';
?>
<style>
    body{
        background-color: #FFFFFF;
    }
    html{
        font-size: 20px;
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

        <a class="flex-item-2 share-guide" href="javascript:;" >
            <i class="iconfont green f28">&#xe644;</i>
        </a>
    </div>
</header>
<div class="pt50"></div>
<div class="clearfix">
    <img src="/assets/images/game/baby/img/banner_1.jpg" style="width:32rem;" class="db bc" />
    <div class="lottery-deadline">
        <p>
            活动时间：2月1日当天 <br/>
            抽奖须知：现金券使用有效期截至2月3日
        </p>
    </div>
</div>
<div class="lottery-wrap bc">
    <div class="pw80 bc" >
        <div id="lottery" class="clearfix">
            <div class="lottery-unit lottery-unit-0">
                <img src="/assets/images/game/baby/img/i1.jpg" class="w db img-0">
            </div>
            <div class="lottery-unit lottery-unit-1">
                <img src="/assets/images/game/baby/img/i2.jpg" class="w db img-1">
            </div>
            <div class="lottery-unit lottery-unit-2">
                <img src="/assets/images/game/baby/img/i3.jpg" class="w db img-2">
            </div>
            <div class="lottery-unit lottery-unit-7">
                <img src="/assets/images/game/baby/img/i4.jpg" class="w db img-7">
            </div>
            <div class="lottery-btn">
                <a href="javascript:void(0);"><img src="/assets/images/game/baby/img/btn.gif" alt="抽奖按钮" class="w db"></a>
            </div>
            <div class="lottery-unit lottery-unit-3">
                <img src="/assets/images/game/baby/img/i5.jpg" class="w db img-3">
            </div>
            <div class="lottery-unit lottery-unit-6">
                <img src="/assets/images/game/baby/img/i6.jpg" class="w db img-6">
            </div>
            <div class="lottery-unit lottery-unit-5">
                <img src="/assets/images/game/baby/img/i7.jpg" class="w db img-5">
            </div>
            <div class="lottery-unit lottery-unit-4">
                <img src="/assets/images/game/baby/img/i8.jpg" class="w db img-4">
            </div>
        </div>
    </div>
</div>
<div id="result" class="none">
    <div class=" red tc bc w ">
        <p class="p5  tc" id="result_name"></p>
        <p class="p5  tc" id="result_description"></p>
        <p class="p5  tc">有效期内下次购物自动使用</p>
        <p class="tc">
            <img src="/images/share-banner.jpg" class="w">
        </p>
        <a class="share-guide" href="javascript:;"></a>
    </div>
</div>
<script>
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
                { text: "立即分享", onClick: function(){ $(".share-guide").trigger("click");; } },
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
        $.post('/choujiang/apply-baby',{v:new Date().getTime(),'id':'<?=$id?>'},function(data){
            $.hideLoading();
            if(data.status){
                lottery.speed=100;
                lottery.prize = data.prize;
                $("#result_name").html(data.name);
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
</script>
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
