<?php
$this->title = $plan->name;
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
<div id="loading_div"></div>
<section class="veiwport  pb50">
<div class="w">
    <!--页面内样式-->
    <style type="text/css">
        .veiwport {
            max-width: inherit;
        }
        .item-tit{ padding-top:0px;}
        .item-inner{
            background-color:#fff;
            border:3px #f7312e solid;
            border-radius:10px;
            overflow: hidden;
        }
        .item-tit{
            padding-bottom: 10px;
        }
        .item-name{
            font-size: 12px;
        }
        .item-price-2 .p-3{
            font-size: 18px;
            line-height: 0.9em;
        }
        .item-photo img{
            width: 97%;
        }
        .item-des{
            color: #0000ff;
        }
        .bg{
            background: #fddee3 ; background-size: cover;
        }
    </style>
    <?php if($plan->source_url){?>
        <img src="<?=\common\component\image\Image::resize($plan->source_url)?>" class="db w" />
    <?php }?>

    <div class="bg pb10">
        <div class="Items">
            <!--<img src="https://img1.mrhuigou.com/group1/M00/00/92/wKgB-Fpe7h-AQySNAAC986bIlZk556.jpg" class="db w" />-->
            <div class="item-wrap item-3" id="cate1">
                <!--tpl-->
            </div>
        </div>
    </div>
    <script id="tpl1" type="text/html">
        <% for(var i=from; i<=to; i++) {%>
        <div class="item">
            <div class="item-padding">
                <div class="item-inner">
                    <div class="item-photo">
                        <a href="<%:=list[i].url%>"><img src="<%:=list[i].image%>" alt="" class="db w" /> </a>
                        <!--已售罄-->
                        <% if(list[i].stock <=0){ %> <i class="item-tip iconfont">&#xe696;</i> <%}%>
                    </div>
                    <div class="item-detail">
                        <a href="<%:=list[i].url%>" class="item-name"> <%:=list[i].name%> </a>
                        <div class="item-des">
                            <%:=list[i].meta_description%>
                        </div>
                        <div class="item-price">
                            <div class="item-price-2">
                                <span class="p-1">优惠价:</span><span class="p-2">￥</span><span class="p-3"><%:=list[i].cur_price%></span>
                            </div>
                            <div class="item-price-1" style="display:none;">
                                ￥<%:=list[i].vip_price%>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <% } %>
    </script>
    <script type="text/javascript">
        var tpl1 = $('#tpl1').html();
        $.getJSON('https://open.mrhuigou.com/mall/v1/affiliate-plan/product?callback=?', {
            code: [
                "<?=$plan->code;?>"
            ]}, function(result) {
            var html= template(tpl1, {list:result.data,from:0,to:(result.data.length-1)});
            $("#cate1").html(html);
        });
        //Ad_Sys_Code('H5-2LTC-DES9');
    </script>

</div>
</section>
<?= fx\widgets\MainMenu::widget(); ?>
<?//=\fx\widgets\Block\Share::widget();?>
<?php
$this->registerJsFile("/assets/js/template.js",['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("/assets/js/jq.min.js",['position' => \yii\web\View::POS_HEAD]);
//$this->registerJsFile("/assets/script/page.js",['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("/assets/js/ad.js",['position' => \yii\web\View::POS_HEAD]);


?>
<?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $data = [
        'title' =>$plan->name,
        'desc' => '口袋超市，物美价廉，当天订单，当天送。',
        'link' => Yii::$app->request->getAbsoluteUrl(),
        'image' => Yii::$app->request->getHostInfo().'/assets/images/zhqd/logo_300x300.jpeg'
    ];
}else{
    $data = [
        'title' =>$plan->name,
        'desc' => '家润每日惠购，物美价廉，当天订单，当天送。',
        'link' => Yii::$app->request->getAbsoluteUrl(),
        'image' => Yii::$app->request->getHostInfo().'/assets/images/mrhuigou_logo.png'
    ];
}?>
<?php fx\widgets\Tools\Share::widget(['data'=>$data]);?>
<?php $this->beginBlock('JS_END') ?>
var referrer = document.referrer;

    if(referrer.indexOf("mp.weixin") > 0 )
    {
     if(location.search.indexOf("neednt_refresh")> 0){

       }else{
    if(location.search.indexOf("?")> 0){
    var reurl = location.protocol+'//'+location.host + location.pathname + location.search+"&neednt_refresh=1";
    }else{
    var reurl = location.protocol+'//'+location.host + location.pathname + location.search+"?neednt_refresh=1";
    }
    location.href = reurl; 
       }
    //    $.getJSON('http://58.56.158.42:8086/test/get-message?callback=?',{date:"获取上一页url="+document.referrer + "&获取当前url="+window.location.href}, function(result){
    //    window.location.reload();
    //});
    }



  



<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_HEAD);
?>

