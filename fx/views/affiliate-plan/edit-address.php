<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2020-3-25
 * Time: 11:12
 */
use \yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
$this->title = '订单确认';
?>
<header class="header">
    <div class="flex-col tc">
        <a class="flex-item-2" href="javascript:history.back()">
            <i class="aui-icon aui-icon-left green f28"></i>
        </a>
        <div class="flex-item-8 f16">
            <?= Html::encode($this->title) ?>
        </div>
        <!--        <a class="flex-item-2" href="--><?//= \yii\helpers\Url::to(['/cart/index']) ?><!--">-->
        <!--            <i class="aui-icon aui-icon-cart green f28"></i>-->
        <!--        </a>-->
    </div>
</header>


<div class="line-a flex-col w flex-middle red mb5">
    <div class="flex-item-12">
        因系统升级，<span class="fb">暂停黄岛区配送！</span>
        给您带来的不便，我们深感抱歉！
    </div>
</div>

<?php $action = \yii\helpers\Url::to([Yii::$app->request->getUrl()])?>
<?php $form = ActiveForm::begin(['id' => 'form-address', 'action'=>  $action,'fieldConfig' => [
    'template' => '<li><div class="t">{label}：</div><div class="c">{input}</div></li>{error}',
    'inputOptions' => ["class" => 'w f14'],
    'errorOptions' => ['class' => 'red pl5']
],]); ?>
<div class="p10 which addresslist pa-t graybg" style="top: 116px; bottom: 50px; overflow-y: scroll;">
    <ul class="line-book mt5">
        <li>
            <div class="t">选择地区：</div>
            <div class="c">
                <div class="weui-cell__bd">
                    <?php $p = isset($address['zone_name']) ? $address['zone_name'] : '山东省';
                    $c = isset($address['city_name']) ? $address['city_name'] : '青岛市';
                    $d = isset($address['district_name']) ? $address['district_name'] : '市北区';
                    ?>
                    <input class="w f14" id="start" name="region" type="text"  value="<?php echo $p.' '.$c.' '.$d;?>">
                </div>
            </div>
        </li>
    </ul>
<div class="c">
    <div class="t">详细地址：</div>
    <div class="weui-cell__bd">
        <textarea name="address_1" placeholder='小区/写字楼/街道+楼号+楼层等' id='address_1'   class='w f14' rows=2 style="height:45px;padding:5px;"><?=isset($address['address']) ?$address['address']:''?></textarea>
    </div>
</div>

</div>
<div class="fx-bottom p5 tc graybg bdt">
    <a class="btn mbtn w greenbtn save_address" href="#">保存</a>
</div>
<?php ActiveForm::end(); ?>
<script>
<?php $this->beginBlock('JS_END') ?>
    /*地址选择*/
    $("#start").cityPicker({
        title: "选择地址",
        onChange: function (picker, values, displayValues) {
            $("#province").val(displayValues[0]);
            $("#city").val(displayValues[1]);
            $("#district").val(displayValues[2]);
        }
    });

$("body").on("click",".save_address",function(){

    var address_1 = $('#address_1').val();
    if(!address_1){
        $.alert("详细地址必须填写");
        $("#address_1").css('border-color',"red");
        return false;
    }
    var start = $('#start').val();
    if(!start){
        $.alert("地区必须填写");
        $("#start").css('border-color',"red");
        return false;
    }
    // $("#form-address").submit();
    $.showLoading("正在提交");
    // $('#form-checkout').submit();
    $.post('/affiliate-plan/edit-address',{region:start,address_1:address_1},function(result){
        //location.href='affiliate-plan/checkout';
        if(result && !result.status){
            $.hideLoading();
            $.alert(result.message);
        }
    },'json');
});

<?php $this->endBlock() ?>
    </script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
$this->registerJsFile("/assets/script/jqweui-picker.js",['depends'=>\fx\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/jqweui-city-picker.js",['depends'=>\fx\assets\AppAsset::className()]);
?>