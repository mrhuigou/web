<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<section class="veiwport  pb50">
    <?php if(!$model->in_range == 1){?>
<!--		<p class="mb5 p5 lh130 whitebg bd-green"> 配送区域仅限：<span class="red">市南区、市北区、崂山区、李沧区、四方区、城阳区、黄岛区</span>。其它区域暂时尚未开通，敬请谅解。-->
<!--		</p>-->
	<?php } else { ?>
        <div class=" mb5 p5 lh130 whitebg bd-green"> 配送区域仅限：<span class="red">市南区、市北区、四方区、李沧区(部分)、崂山区(部分)、<span class="fb">黄岛(暂停配送)</span></span>。其它区域暂时尚未开通，敬请谅解。
            <span class="cp showM h unl green f12">查看详细</span>
            <p class="aboutAd dn">市区配送区域介绍： <span class="red">
                1、西至西镇沿海/
2、南至五四广场沿海一线/
3、东至滨海大道（松岭路）滨海大道北面至世园大道为界，边界外配送崂山路两侧往东至九水东路交接处为界，崂山区不进风景区；
4、重庆路往北至湘潭路为界
5、四流路往北至四流中路与四流北路交界
6、黑龙江路北至湘潭路为界</span>
                <br>黄岛区配送区域介绍：
            <span class="red fb">
                黄岛区域暂停配送
                <!----西至昆仑山路，北至淮河路；南至海边--></span>
            </p>

        </div>
	<?php } ?>

    <div class="flex-col tc whitebg bdb">
<!--	    <a class="flex-item-6 p5 cur bdr f14">手工输入</a>-->
<!--	    <a class="flex-item-6 p5 f14 gray9 " id="pop-map">-->
<!--		    <i class="iconfont vm f14">&#xe622;</i>地图定位-->
<!--	    </a>-->
    </div>
    <?php $action = \yii\helpers\Url::to([Yii::$app->request->getUrl()])?>
	<?php $form = ActiveForm::begin(['id' => 'form-address', 'action'=>  $action,'fieldConfig' => [
		'template' => '<li><div class="t">{label}：</div><div class="c">{input}</div></li>{error}',
		'inputOptions' => ["class" => 'w f14'],
		'errorOptions' => ['class' => 'red pl5']
	],]); ?>
	<?= $form->field($model, 'lat', ['template' => '{input}'])->hiddenInput(['id' => 'address-lat'])->label(false) ?>
	<?= $form->field($model, 'lng', ['template' => '{input}'])->hiddenInput(['id' => 'address-lng'])->label(false) ?>
	<?= $form->field($model, 'province', ['template' => '{input}'])->hiddenInput(['id' => 'province'])->label(false) ?>
	<?= $form->field($model, 'city', ['template' => '{input}'])->hiddenInput(['id' => 'city'])->label(false) ?>
	<?= $form->field($model, 'postcode', ['template' => '{input}'])->hiddenInput(['value' => '266001']) ?>
	<?= $form->field($model, 'poiaddress', ['template' => '{input}'])->hiddenInput(['id' => 'poiaddress'])?>
	<?= $form->field($model, 'poiname',['template' => '{input}'])->hiddenInput(['id' => 'poiname']) ?>
	<?= $form->field($model, 'district',['template' => '{input}'])->hiddenInput(['id' => 'district'])?>
	<??>
    <ul class="line-book mt5">
        <?php if(!$model->in_range == 1){?>
            <li>
                <div class="t">选择地区：</div>
                <div class="c">
            <div class="weui-cell__bd">
                <?php $p = $model->province ? $model->province : '山东省';
                $c = $model->city ? $model->city : '青岛市';
                $d = $model->district ? $model->district : '市北区';
                ?>
                <input class="w f14" id="start" type="text"  value="<?php echo $p.' '.$c.' '.$d;?>">
            </div>
                </div>

            </li>
        <?php }else{?>
<!--            <li><div class="t">所在城市：</div><div class="c">青岛市</div><div class="d">青岛市</div> </li>-->
            <li>
                <div class="t">选择地区：</div>
                <div class="c">
                    <div class="weui-cell__bd">
                        <?php $p = $model->province ? $model->province : '山东省';
                        $c = $model->city ? $model->city : '青岛市';
                        $d = $model->district ? $model->district : '市北区';
                        ?>
                        <input class="w f14" id="start" type="text"  value="<?php echo $p.' '.$c.' '.$d;?>">
                    </div>
                </div>
            </li>
            <p class="red pl5 error_district"></p>
        <?php }?>
	<?= $form->field($model, 'address',['template' => '{label}<li>{input}</li>{error}'])->textarea(["placeholder" => '小区/写字楼/街道+楼号+楼层等','id'=>'address','class'=>'w f14 ','rows'=>2,'style'=>"height:45px;padding:5px;"])?>
	<?= $form->field($model, 'username', ['inputOptions' => ["placeholder" => '请填写收货人姓名']]) ?>
	<?= $form->field($model, 'telephone', ['inputOptions' => ["placeholder" => '请填写收货人电话号码']]) ?>
        <?php if(!$model->in_range == 1){?> <??>
            <?= $form->field($model, 'is_default',['template' => "<div class='p5'>{input} {label}</div>",])->hiddenInput([
                    'template' => "<div class='p5'>{input} {label}</div>",
                    'value'=>0
            ])->label(false) ?>
        <?php }else{?>
            <?= $form->field($model, 'is_default')->checkbox(['template' => "<div class='p5'>{input} {label}</div>"]) ?>
        <?php }?>
	</ul>
	<div class=" bdt  p10 w tc ">

        <a href="javascript:void(0)"  class='btn mbtn greenbtn w SubmitBtn'>保存地址 </a>
	</div>
	<?php ActiveForm::end(); ?>
<!--	<div id="map-contain" style="position:fixed; left:0; top:0; width:100%; height:100%; border:none;z-index:999;display: none;" class="layermchild  layermanim">-->
<!--			<div style="bottom: 0px;" class="pa-t">-->
<!--				<iframe width="100%" height="100%" frameborder="0" src="https://apis.map.qq.com/tools/locpicker?search=1&type=1&key=GNWBZ-7FSAR-JL5WY-WIDVS-FHLY2-JVBEC&referer=365jiarun" id="mapPage"></iframe>-->
<!--			</div>-->
<!--	</div>-->

</section>
<script>
<?php $this->beginBlock("JS_QQDiTu") ?>
//window.addEventListener('message', function(event) {
//    $("#map-contain").hide();
//    $.showIndicator();
//// 接收位置信息，用户选择确认位置点后选点组件会触发该事件，回传用户的位置信息
//    var loc = event.data;
//    console.log(loc);
//    if (loc && loc.module == 'locationPicker') {//防止其他应用也会向该页面post信息，需判断module是否为'locationPicker'
//        $.post('<?//=\yii\helpers\Url::to('/address/geo')?>//',{lat:loc.latlng.lat,lng:loc.latlng.lng},function(data){
//            if(data){
//                if(loc.poiname=="我的位置"){
//                    $("#poiname").val(data.title);
//                    $("#poiaddress").val(data.address);
//                    $("#address").val(data.address+data.title);
//                }else{
//                    $("#poiname").val(loc.poiname);
//                    $("#poiaddress").val(loc.poiaddress.replace(data.province+data.city,""));
//                    $("#address").val(loc.poiaddress.replace(data.province+data.city,"")+loc.poiname);
//                }
//                $("#province").val(data.province);
//                $("#city").val(data.city);
//                $("#district").val(data.district);
//                $("#address-lat").val(data.lat);
//                $("#address-lng").val(data.lng);
//            }
//        });
//        $.hideIndicator();
//    }
//}, false);
$("#pop-map").click(function(){
    $("#map-contain").show();
});
/*地址选择*/
$("#start").cityPicker({
    title: "选择地址",
    onChange: function (picker, values, displayValues) {
        $("#province").val(displayValues[0]);
        $("#city").val(displayValues[1]);
        $("#district").val(displayValues[2]);

    }
});

$("body").on("click",".showM.h",function () {
    $(this).removeClass("h").addClass("s").text("收起");
    $(".aboutAd").show();
})

$("body").on("click",".showM.s",function () {
    $(this).removeClass("s").addClass("h").text("查看详细");
    $(".aboutAd").hide();
});
$("body").on("click",".SubmitBtn",function () {
    if( !$("#district").val() ||  $("#district").val() == '请选择'){
        $('.error_district').html("请选择地区");
        $('.error_district').show();

    }

    $("#form-address").submit();
});



<?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS_QQDiTu'], \yii\web\View::POS_READY);
$this->registerJsFile("/assets/script/jqweui-picker.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/jqweui-city-picker.js",['depends'=>\h5\assets\AppAsset::className()]);
?>

