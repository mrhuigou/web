<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/9/5
 * Time: 16:58
 */
use yii\helpers\Html;
$this->title='管理活动';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18">管理活动</h2>
    <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>
<section class="veiwport">
    <ul class="filter blueFilter four  mb10 ">
        <li class="<?=$view?'':'cur'?>"><a href="<?=\yii\helpers\Url::to(['/club-activity/manage','id'=>$model->id])?>">管理活动</a></li>
        <li class="<?=$view=='enroll'?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['/club-activity/manage','id'=>$model->id,'view'=>'enroll'])?>">报名</a></li>
        <li class="<?=$view=='comment'?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['/club-activity/manage','id'=>$model->id,'view'=>'comment'])?>">评论</a></li>
        <li class="<?=$view=='like'?'cur':''?>"><a href="<?=\yii\helpers\Url::to(['/club-activity/manage','id'=>$model->id,'view'=>'like'])?>">赞</a></li>
    </ul>

    <div class="whitebg clearfix tc bdt bdb">
        <div class="fl pw30 bdr pt25" style="padding-bottom:28px;">
            订单数
            <p class="f14 gray9"><?=count($model->user)?></p>
        </div>
        <div class="fl pw35 lh44 bdr">
            <p class="bdb">报名数 <span class="gray9"><?=$model->tickets?></span></p>
            <p>赞 <span class="gray9"><?=$model->like_count?></span></p>
        </div>
        <div class="fl pw35 lh44 ">
            <p class="bdb">阅读 <span class="gray9"><?=$model->click_count?></span></p>
            <p>评论 <span class="gray9"><?=$model->comment_count?></span></p>
        </div>
    </div>

    <p class="gray9 pt15 pl10 pb5">内容修改</p>
    <div class="tc whitebg p15">
        <p class="bdb pb10 tl mb15"><?=Html::encode($model->title)?></p>
        <a href="javascript:;" class="blue" data-confirm="请使用PC端修改内容"><i class="iconfont pr5 vm-2">&#xe615;</i><span>修改活动内容</span></a>
    </div>

    <p class="gray9 pt15 pl10 pb5">您可以进行一下操作</p>
    <div class="row tc whitebg">
        <a class="col-3  bdr bdb p10" href="javascript:;">
            <i class="iconfont blue-">&#xe659;</i>
            <p class="mt5">活动二维码</p>
        </a>
        <a class="col-3  bdr bdb p10 exportTri" href="javascript:;">
            <i class="iconfont blue-">&#xe657;</i>
            <p class="mt5">导出名单</p>
        </a>
        <a class="col-3  bdr bdb p10" href="<?=\yii\helpers\Url::to(['/club-activity/detail','id'=>$model->id])?>">
            <i class="iconfont blue-">&#xe658;</i>
            <p class="mt5">查看</p>
        </a>
        <a class="col-3  bdr bdb p10" href="javascript:;">
            <i class="iconfont blue-">&#xe649;</i>
            <p class="mt5">群发消息</p>
        </a>
        <div class="col-3  bdr bdb p10" id="scanQRCode">
            <i class="iconfont blue-">&#xe65a;</i>
            <p class="mt5">检票</p>
        </div>
        <?php if($model->status){?>
        <a class="col-3  bdr bdb p10"  data-confirm="您确认要关闭报名吗？" href="<?=\yii\helpers\Url::to(['/club-activity/close','id'=>$model->id])?>">
            <i class="iconfont blue-">&#xe617;</i>
            <p class="mt5">关闭报名</p>
        </a>
    <?php }else{ ?>
        <a class="col-3  bdr bdb p10" data-confirm="您确认要开放报名吗？" href="<?=\yii\helpers\Url::to(['/club-activity/open','id'=>$model->id])?>">
            <i class="iconfont blue-">&#xe61a;</i>
            <p class="mt5">开放报名</p>
        </a>
    <?php } ?>
    </div>
</section>

    <!--导出名单 弹层-->
    <div class="p5 tc gray6 exportPop" style="display:none;">
        <!-- <div class="whitebg">
            <p class="bdb p10">发送到邮箱</p>
        </div>
        <span class="btn w mbtn transbtn mt5">取消</span> -->
        <div class="whitebg p10">
            <div class="fl mb10 f13">请输入常用邮箱</div>
            <input type="text" class="input minput w" placeholder="输入你的邮箱" autofocus>
            <a href="#" class="btn w mbtn bluebtn mt10" id="sendmailbtn">发送到邮箱</a>
        </div>
        <span class="btn w mbtn graybtn cancel mt5">取消</span>
    </div>
    <!--导出到邮箱 弹层-->
    <div class="p5 tc gray6 exportEmailPop" style="display:none;">
        <input type="text" class="input minput w" placeholder="输入你的邮箱" autofocus>
        <a href="#" class="btn w mbtn transbtn mt5">发送到邮箱</a>
        <span class="btn w mbtn transbtn mt5">取消</span>
    </div>
<?=h5\widgets\Wx\ScanQRCode::widget(['activity_id'=>$model->id]);?>

<?=h5\widgets\Wx\Upload::widget()?>
<?php $this->beginBlock("JS_BaiDu")?>
        //导出名单
        $(".exportTri").click(function(){
            maskdiv($(".exportPop"),"bottom");
        })
        $(".exportPop .cancel").click(function(){
            $(".maskdiv").fadeOut();
            $(".exportPop").slideUp();
        })

        $(".exportPop p").click(function(){
            $(".exportPop").slideUp();
            maskdiv($(".exportEmailPop"),"bottom");
        })
        $(".exportEmailPop .btn").click(function(){
            $(".maskdiv").fadeOut();
            $(".exportEmailPop").slideUp();
        })

        $("#sendmailbtn").on("click",function(){
            var email = $(".exportPop input").val();
            if(email.match(/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/g)){
                $.get("<?=\yii\helpers\Url::to(['/club-activity/sendmail','id'=>$model->id])?>", { email: email },function(data){
                    swal("发送成功", "", "success");
                    $(".maskdiv").fadeOut();
                    $(".exportPop").slideUp();
                } );
            }else{
            swal("失败", '邮箱格式错误', "error");
                return false;
            }
        });

<?php $this->endBlock()?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_BaiDu'],\yii\web\View::POS_END);
?>