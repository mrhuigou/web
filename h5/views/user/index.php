<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '用户中心';
?>
<header class="header">
	<div class="flex-col tc">
		<a class="flex-item-2" href="javascript:history.back();">
			<i class="aui-icon aui-icon-left green f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?=Html::encode($this->title)?>
		</div>
		<a class="flex-item-2" href="<?php echo \yii\helpers\Url::to(['/user/edit-myinfo'])?>">
			<i class="aui-icon aui-icon-settings green f28"></i>
            <i class="vm f14 lh150 green" >设置</i>
		</a>
	</div>
</header>
<div class="content">
<section class="veiwport mb50">
	<div class="w">
		<!--用户头像-->
        <div class="head-img" style="padding: 0 0;">
		<div class="  flex-col flex-middle p10">
			<div class="flex-item-3">
				<a href="<?php echo \yii\helpers\Url::to(['/user/avatar'])?>" style="line-height: 100px;">
					<img src="<?= \common\component\image\Image::resize(\Yii::$app->user->identity->photo, 100, 100) ?>"
					     alt="<?= \Yii::$app->user->identity->nickname ?>" class="ava mava">
				</a>
			</div>
			<div class="flex-item-6 tc pt10" >
                <?= \Yii::$app->user->identity->nickname ?>
				<p class="f12 " ><?= \Yii::$app->user->identity->signature ? \Yii::$app->user->identity->signature : '这家伙很懒没有留下签名' ?></p>
			</div>
			<div class="flex-item-3 tr">
				<a href="<?php echo \yii\helpers\Url::to(['/user/qrcode'])?>" style="line-height: 100px;color: white;">
					<i class="aui-icon aui-icon-qrcode vm"></i>
				</a>
			</div>
		</div>
        <div class="flex-col tc lh37 bg-wh " style="opacity: 0.7;">
            <a class="flex-item-4" href="<?= \yii\helpers\Url::to(['collect/index']) ?>">
                <i class="aui-icon aui-icon-favor" style="font-size: 16px;"></i>
                商品收藏
            </a>
            <a class="flex-item-4 " href="<?= \yii\helpers\Url::to(['foot-print/index']) ?>">
                <i class="aui-icon aui-icon-footprint" style="font-size: 16px;"></i>我的足迹
            </a>
            <a class="flex-item-4" href="<?= \yii\helpers\Url::to(['/message/index']) ?>">
                <i class="aui-icon aui-icon-notification" style="font-size: 16px;"></i>消息
            </a>
        </div>
        </div>
		<!--订单状态-->
        <div class="mt5  whitebg p10 bdb clearfix"><span class="fl"><i class="iconfont blue f18" style="line-height: 14px;">&#xe61f;</i>我的订单</span><a href="<?= \yii\helpers\Url::to(['/order/index']) ?>" class="fr">查看所有订单</a></div>
		<div class="flex-col tc bg-wh gray6  mb5 p5">
			<a href="<?= \yii\helpers\Url::to(['/order/index', 'order_status' => 'NOPAY']) ?>" class="flex-item-3 flex-middle pr">
                <i class="aui-icon aui-icon-pay"></i>
                <p>待付款</p>
				<sup class="info-point info-point-red pa-rt <?=$nopay?'':'none'?>"><?=$nopay?></sup></a>
			<a href="<?= \yii\helpers\Url::to(['/order/index', 'order_status' => 'PAYED']) ?>" class=" flex-item-3  flex-middle pr"><i
					class="aui-icon aui-icon-send"></i><sup class="info-point info-point-red pa-rt <?=$noway?'':'none'?>"><?=$noway?></sup><p>待发货</p></a>
			<a href="<?= \yii\helpers\Url::to(['/order/index', 'order_status' => 'ONWAY']) ?>" class="flex-item-3  flex-middle pr"><i
					class=" aui-icon aui-icon-deliver"></i><p>待收货</p><sup class="info-point info-point-red pa-rt <?=$onway?'':'none'?>"><?=$onway?></sup></a>
			<a href="<?= \yii\helpers\Url::to(['/order/return']) ?>" class="flex-item-3  flex-middle"><i class="aui-icon aui-icon-refund"></i><p>售后订单</p></a>
		</div>
		<a class="line-a db w clearfix  mt5" href="<?= \yii\helpers\Url::to(['/address/index']) ?>">
            <span class="fl"><em class="iconfont blue f18" style="line-height: 14px;">&#xe622;</em>收货地址</span>
			<i class="iconfont fr green">添加收货地址</i>
		</a>
        <a class="line-a db w clearfix  mt5" href="<?= \yii\helpers\Url::to(['/invoice/index']) ?>">
            <span class="fl"><em class="iconfont blue f18" style="line-height: 14px;">&#xe6af;</em>发票信息</span>
            <i class="iconfont fr green">发票管理</i>
        </a>
<!--        --><?php //echo \h5\widgets\Block\UserShareBlock::widget()?>
		<div class="mt5  whitebg p5 "><i class="aui-icon aui-icon-pay org f18"></i>我的钱包</div>
		<div class="flex-col flex-center tc whitebg bdt bdb  p10 mb5">
            <?php if(Yii::$app->session->get('source_from_agent_wx_xcx')){?>
                <a class="flex-item-4  flex-middle  p10 bdr " href="<?php echo \yii\helpers\Url::to(['/user/balance']) ?>">
                    <i class="aui-icon aui-icon-recharge blue  "></i>
                    余额
                </a>
                <a class="flex-item-4  flex-middle bdr  p10" href="<?php echo \yii\helpers\Url::to(['/user-coupon/index']) ?>">
                    <i class="aui-icon aui-icon-ticket org "></i>
                    优惠券
                </a>
                <a class="flex-item-4  flex-middle p10" href="<?php echo \yii\helpers\Url::to(['/user-hongbao/index']) ?>">
                    <i class="aui-icon aui-icon-redpacket red"></i>
                    红包
                </a>
            <?php }else{?>
                <a class="flex-item-4  flex-middle  p10 bdr " href="<?php echo \yii\helpers\Url::to(['/user/balance']) ?>">
                    <i class="aui-icon aui-icon-recharge blue  "></i>
                    余额
                </a>
                <a class="flex-item-4  flex-middle bdr  p10" href="<?php echo \yii\helpers\Url::to(['/user-coupon/index']) ?>">
                    <i class="aui-icon aui-icon-ticket org "></i>
                    优惠券
                </a>
                <a class="flex-item-4  flex-middle bdr p10" href="<?php echo \yii\helpers\Url::to(['/user-hongbao/index']) ?>">
                    <i class="aui-icon aui-icon-redpacket red"></i>
                    红包
                </a>
<!--                <a class="flex-item-3  flex-middle  p10  " href="--><?php //echo \yii\helpers\Url::to(['/site/go-to','code'=>'hssrwd']) ?><!--">-->
<!--                    <i class="iconfont vm green"> &#xe6b5;</i>-->
<!--                    <span class="vm">理财</span>-->
<!--                </a>-->
            <?php }?>


		</div>

		<a class="line-a db w clearfix mt5 " href="<?= \yii\helpers\Url::to(['/user/security-center']) ?>">
            <span class="fl"><em class="aui-icon aui-icon-safe org f18" style="line-height: 20px;"></em>安全中心</span>
			<span class="fr org  vm"><i class="iconfont fr"></i>更多</span>
		</a>
		<div class="flex-col flex-center tc whitebg  bdb  p10">
			<a class="flex-item-4  flex-middle  p10 bdr bdb" href="<?= \yii\helpers\Url::to(['/user/security-set-telephone']) ?>">
				<i class="aui-icon aui-icon-phone blue"></i>
				<p>手机验证</p>
			</a>
            <a class="flex-item-4  flex-middle  p10 bdr bdb" href="<?= \yii\helpers\Url::to(['/user/security-set-email']) ?>">
                <i class="aui-icon aui-icon-message blue"></i>
                <p>邮箱验证</p>
            </a>
			<a class="flex-item-4  flex-middle  p10 bdb" href="<?= \yii\helpers\Url::to(['/user/security-update-paymentpwd']) ?>">
				<i class="aui-icon aui-icon-vipcard blue"></i>
				<p>支付密码</p>
			</a>
            <a class="flex-item-4  flex-middle bdr  p10" href="<?= \yii\helpers\Url::to(['/user/security-update-password']) ?>">
                <i class="aui-icon aui-icon-lock org"></i>
                <p>密码修改</p>
            </a>
<!--            <a class="flex-item-4  flex-middle bdr  p10" href="--><?php //= \yii\helpers\Url::to(['/user/security-ums-auth']) ?><!--">-->
<!--                <i class="aui-icon aui-icon-vip org"></i>-->
<!--                <p>实名验证</p>-->
<!--            </a>-->
            <a class="flex-item-4  flex-middle   p10 bdr" href="<?= \yii\helpers\Url::to(['/user/bind']) ?>">
                <i class="aui-icon aui-icon-my org"></i>
                <p>账户绑定</p>
            </a>
		</div>
		<a class="line-a db w clearfix p10 " href="tel:4008556977" target="_blank">
			客服热线：0532-55729957
			<i class="iconfont fr"></i>
		</a>
        <?php if(Yii::$app->user->getId()==17412){?>
        <div class="flex-col flex-center tc whitebg bdt bdb  p10 mb5">
            <a class="flex-item-4  flex-middle  p10 bdr "  href="javascript:void(0)" id="pushbind">
                <i class="aui-icon aui-icon-recharge blue  "></i>
                绑定用户
            </a>
            <a class="flex-item-4  flex-middle bdr  p10" href="javascript:void(0)" id="pushmsg">
                <i class="aui-icon aui-icon-ticket org "></i>
                发送消息
            </a>
            <a class="flex-item-4  flex-middle  p10" href="<?php echo \yii\helpers\Url::to(['/site/test-wx'])?>">
                <i class="aui-icon aui-icon-redpacket red"></i>
                测试微信支付
            </a>
        </div>
        <?php }?>
	</div>
</section>
</div>
<?= h5\widgets\MainMenu::widget(); ?>


<script>
    <?php if(Yii::$app->user->getId()==17412){?>
    <?php
    $this->beginBlock('JS_INIT')
    ?>

    $("#pushbind").click(function(){
        var push = api.require('push');
        push.bind({
            userName: '艾文',
            userId: '17412'
        }, function(ret, err){
            alert('ret:');
            if( ret ){
                alert( JSON.stringify( ret) );
            }else{
                alert( JSON.stringify( err) );
            }
        });
    });
    $("#pushunbind").click(function(){
        var push = api.require('push');
        push.unbind({
            userName: '艾文',
            userId: '17412'
        }, function(ret, err){
            if( ret ){
                alert( JSON.stringify( ret) );
            }else{
                alert( JSON.stringify( err) );
            }
        });
    });

    $("#pushmsg").click(function(){
        var now = Date.now();
        var appKey = $.sha1("A6053443611250" + "UZ" + "92E004D4-24AB-6151-B89B-59872CF01C55" + "UZ" + now) + "." + now;
        api.ajax({
            url : 'https://p.apicloud.com/api/push/message',
            method : "post",
            headers: {
                "X-APICloud-AppId": "A6053443611250",
                "X-APICloud-AppKey": appKey,
                "Content-Type": "application/json"
            },
            dataType: "json",
            data: {
                "body": {
                    "title": "逆天福利来袭",
                    "content": "满118元免费送鸳鸯锅1个；酸奶全场满9.99元送优优湿巾2包！",
                    "type": 1, //– 消息类型，1:消息 2:通知
                    "platform": 0, //0:全部平台，1：ios, 2：android
                    "userIds": '17412'
                }
            }
        }, function(ret, err) {
            //coding...
            alert(JSON.stringify(ret))
            alert(JSON.stringify(err))
        });
    });


    <?php $this->endBlock() ?>
    <?php
    $this->registerJsFile("/assets/script/jquery.sha1.js",['depends'=>\h5\assets\AppAsset::className()]);
    $this->registerJs($this->blocks['JS_INIT'],\yii\web\View::POS_END);
    ?>
    <?php }?>
</script>
