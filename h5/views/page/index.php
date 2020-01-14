<?php
$this->title =  $page->description->title;
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<div id="loading_div"></div>
<section class="veiwport  pb50">
<div class="w">
	<?=\yii\helpers\Html::decode($page->description->description)?>
</div>
</section>
<?= h5\widgets\MainMenu::widget(); ?>
<?//=\h5\widgets\Block\Share::widget();?>
<?php
$this->registerJsFile("/assets/js/template.js",['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("/assets/js/jq.min.js",['position' => \yii\web\View::POS_HEAD]);
//$this->registerJsFile("/assets/script/page.js",['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("/assets/js/ad.js",['position' => \yii\web\View::POS_HEAD]);


?>
<?php  if(strtolower(Yii::$app->request->get("sourcefrom")) == 'zhqd'){
    $data = [
        'title' =>$page->description->title,
        'desc' => '口袋超市，物美价廉，当天订单，当天送。',
        'link' => Yii::$app->request->getAbsoluteUrl(),
        'image' => Yii::$app->request->getHostInfo().'/assets/images/zhqd/logo_300x300.jpeg'
    ];
}else{
    $data = [
        'title' =>$page->description->title,
        'desc' => '家润每日惠购，物美价廉，当天订单，当天送。',
        'link' => Yii::$app->request->getAbsoluteUrl(),
        'image' => Yii::$app->request->getHostInfo().'/assets/images/mrhuigou_logo.png'
    ];
}?>
<?php h5\widgets\Tools\Share::widget(['data'=>$data]);?>
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

