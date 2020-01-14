<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/22
 * Time: 15:28
 */
$this->title = $lottery->title;
?>
<style type="text/css">
    * { margin: 0; padding: 0;}
    body { font-family: "Microsoft Yahei"; background: #c90a1a}
    .rotary { position: relative;  width: 30rem; height: 30rem; margin: -45px 10px 10px 10px; background-image: url(/assets/images/choujiang/g_1111.png) ;background-repeat:no-repeat;background-size:contain;}
    .hand { position: absolute; left: 11.49rem; top:  11.49rem; width: 7rem; height: 7rem; cursor: pointer;}
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
<div class="pt50"></div>
<div class="clearfix">
    <img src="/assets/images/choujiang/top.jpg" class="w">
    <div class="rotary bc">
        <img class="hand" src="/assets/images/choujiang/z_1.png" alt="">
    </div>
</div>
<div id="my_result">
    <?php if($my_self){?>
        <?php foreach ($my_self as $value){?>
            <div class="br5 opc-f p10 m10" id="my_self">
                <div class="flex-col activity-1-list">
                    <div class="flex-item-2 tc">
                        <img src="<?=\common\component\image\Image::resize($value->customer->photo,100,100)?>" alt="头像" width="47" height="47" class="img-circle">
                    </div>
                    <div class="flex-item-6 pl10">
                        <p class="pt5"><?=$value->customer->nickname?></p>
                        <p class="gray6 f12 pt2"><?=date('m/d H:i:s',$value->creat_at)?></p>
                    </div>
                    <div class="flex-item-4 tr  org">
                        <?=$value->prize->title?>
                        <br>
                        <div class="pt10 db"><a class="green" href="<?php echo \yii\helpers\Url::to(['site/index'])?>">去使用>></a></div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<!--<div class="m10 tc"><a class="" href="--><?php //echo \yii\helpers\Url::to(['/page/index','page_id'=>2581])?><!--">-->
<!--        <img src="/assets/images/choujiang/mall_1111.jpg" class="w">-->
<!--    </a></div>-->
<div class="br5 opc-f p10 m10">
    <div class="tit-- mt10 mb10">看看大家手气</div>
    <div id="scrollDiv" style="height: 204px;min-height:68px;overflow: hidden;">
        <ul id="user_list" >
            <?php if($history){ ?>
                <?php foreach ($history as $value){?>
                    <li class="flex-col activity-1-list">
                        <div class="flex-item-2 tc">
                            <img src="<?=\common\component\image\Image::resize($value->customer->photo,100,100)?>" alt="头像" width="47" height="47" class="img-circle">
                        </div>
                        <div class="flex-item-6 pl10">
                            <p class="pt5"><?=$value->customer->nickname?></p>
                            <p class="gray6 f12 pt2"><?=date('m/d',$value->creat_at)?></p>
                        </div>
                        <div class="flex-item-4 tr  org">
                            <?=$value->prize->title?>
                        </div>
                    </li>
                <?php } ?>
            <?php }else{ ?>
                <p class="tc lh200" id="result_prize">暂时没有中奖信息</p>
            <?php } ?>
        </ul>
    </div>
    <!-- 活动规则 -->
    <div class="tit-- mt15 mb10">活动规则</div>
    <ul class="ul ul-decimal ml25 f14">
        <li>活动时间：2月23日~2月24日</li>
        <li>每个用户ID只能抽1次</li>
<!--        <li>自领取之日起至12月29日内有效</li>-->
        <li>每笔订单限使用1张</li>
        <li>如有疑问请联系客服 400-968-9870</li>
    </ul>
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
<script id="tpl" type="text/html">
    <div class="br5 opc-f p10 m10">
        <div class="flex-col activity-1-list" id="my_self">
            <div class="flex-item-2 tc">
                <img src="<%:=list.photo%>" alt="头像" width="47" height="47" class="img-circle">
            </div>
            <div class="flex-item-6 pl10">
                <p class="pt5"><%:=list.nickname%></p>
                <p class="gray6 f12 pt2"><%:=list.datetime%></p>
            </div>
            <div class="flex-item-4 tr org">
                <%:=list.des%>
            </div>
        </div>
    </div>
</script>
<script>
    <?php $this->beginBlock('J_Reviews') ?>
    var $hand = $('.hand');
    var time_count=0;
    $hand.click(function(){
        $.showLoading();
        $.post('/choujiang/apply',{v:new Date().getTime(),'id':'<?=$id?>'},function(res){
            $.hideLoading();
            if(res.status){
                time_count++;
                //console.log(res.angle);
                $("#result_name").html(res.title);
                $("#result_description").html(res.description);
                rotateFunc(res.angle,res.message);
            }else{
                $.alert(res.message);
            }
        });
    });
    var rotateFunc = function(angle,text){
        $hand.stopRotate();
        $hand.rotate({
            angle: 0,
            duration: 5000,
            animateTo: angle + 2880,
            callback: function(){
                //$.alert(text);
                $.alert($("#result").html());
                $.modal({
                    title: "恭喜您获得",
                    text: $("#result").html(),
                    buttons: [
                        { text: "邀请好友来抽奖", onClick: function(){ $(".share-guide").trigger("click");; } },
                    ]
                });
                loading();
                $("#user_count").text(Number($("#user_count").text())+1);
            }
        });
    };
    var tpl = $('#tpl').html();
    function loading(){
        $.post('<?=\yii\helpers\Url::to(['/choujiang/result'])?>',{'lottery_id':'<?=$id?>'},function(res){
            if(res){
                var html= template(tpl, {list:res.data});
                $("#my_result").html(html);
            }
        },'json');
    }
    $("#scrollDiv").Scroll({line:3,speed:1500,timer:3000});

    <?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJsFile("/assets/script/jquery.rotate.min.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/jquery.rowscroll.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJs($this->blocks['J_Reviews'],\yii\web\View::POS_END);
?>
<?php echo h5\widgets\Tools\Share::widget(['data'=>[
    'title' => $lottery->title,
    'desc' => '酸奶、水果、饮料、日常用品。来每日惠购就“购”了!',
    'link' => \yii\helpers\Url::to(['/choujiang/common','id'=>Yii::$app->request->get("id")],true),
    'image' => Yii::$app->request->getHostInfo() . '/assets/images/hongbao.png',
    //'hidden_status'=>'hidden',
]]);
?>
