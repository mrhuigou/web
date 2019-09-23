<?php ?>
<div style="background-color:#fee4cd;padding-bottom:100px;">

    <!-- banner -->
    <img class="w" src="../assets/images/topic/licai/d1.jpg" />

    <img class="w" src="../assets/images/topic/licai/d2.jpg" />

    <img class="w" src="../assets/images/topic/licai/d3.jpg" />
    <!-- 为什么选择 描述一 -->
    <div class="pl15 pr15 pt5 f13 lh150 ti2">
        <p class="p10 mb10" style="background-color: #f7d8bc;color: #9d5500;">
            青岛华通国有资本运营（集团）有限责任公司成立于2008年2月，为国有独资的市政府直属投资公司，履行政府资本运营和投融资主体职能。
        </p>
        <p class="p10 mb10" style="background-color: #f7d8bc;color: #9d5500;">
            华通集团旗下名企云集，像澳柯玛、青岛银行、青食、孚德、华侨国际饭店、青岛中国旅行社...
        </p>
        <p class="p10" style="background-color: #f7d8bc;color: #9d5500;">
            “尚融网贷”是华通集团旗下互联网金融平台，主要业务是为中小微企业搭建高效便捷的融资渠道，为投资个体开发可信稳健的投资产品。尚融网贷作为专业的理财神器，专注于短期理财，年化利率高，<span class="fb">相比银行高20%—40%</span>。
        </p>
    </div>


    <img class="w" src="../assets/images/topic/licai/d4.jpg" />
    <!-- 为什么选择 描述二 -->
    <p class="pl20 pr20 ti2" style="color: #9d5500;">
        “尚融网贷”依托华商汇通产品研发优势和严格风控体系，为投资个体开发可信稳健的投资产品，自2015年二月上线至今无坏账记录。
        <span class="fb">您的钱安全无忧，您的收益稳健可观，尚融网贷，家润为您推荐！</span>
    </p>


    <!-- 新用户注册有礼 -->
    <img class="w mt20" src="../assets/images/topic/licai/d5.jpg" />

    <img class="w" src="../assets/images/topic/licai/d6.jpg" />

    <!-- 同意服务协议 -->
    <label class="tc pb5 db">
        <input type="checkbox" id="signup_agreement" class="vm" /><span class="vm" style="color:#9c5513;">同意<a href="https://m.sunraising.com.cn/agreement/reg_agreement.html">《尚融网贷服务协议》</a> </span>
        <br>
        <span class="red agreement" >您必须同意《尚融网贷服务协议》</span>

    </label>

    <!-- 注册 -->
<!--    <a href="javascript:void(0)"  class="db sunraising-submit"><img class="w" src="../assets/images/topic/licai/d7-0.jpg" /></a>-->
    <a href="javascript:void(0)"  class="db sunraising-submit"><img class="w" src="../assets/images/topic/licai/d7.jpg" /></a>

    <!-- 拨打热线 -->
    <img class="w" src="../assets/images/topic/licai/d8.jpg" />

</div>
<script>
    <?php $this->beginBlock("JS") ?>
    $("#signup_agreement").click(function(){
        if($("#signup_agreement").is(':checked')){
            $(".agreement").hide();
        }else {
            $(".agreement").show();
        }
    });

    $(".sunraising-submit").click(function(){
//        layer.open({
//            content: '即将上线，敬请期待！'
//            ,btn: '我知道了'
//        });
        if($("#signup_agreement").is(':checked')){
            $.post('<?php echo \yii\helpers\Url::to(["/site/sunraising-signup"])?>',{sign:1},function(data){
                if(data.status){
                    location.href=data.redirect;
                }
            },'json');
        }else{
            layer.open({
                content: '您必须同意服务协议'
                ,btn: '我知道了'
            });
            $(".agreement").show();
        }
    });
    <?php $this->endBlock() ?>
</script>

<?php
$this->registerJs($this->blocks['JS'], \yii\web\View::POS_READY);
?>
