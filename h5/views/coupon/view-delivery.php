<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/11/5
 * Time: 13:48
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title ='优惠券详情';
?>
<style>
    .activity-1-coupon::after, .activity-1-coupon::before {
        background-color: #f4f4f4;
    }
</style>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport">
	<div class=" pb40 mb20">
		<?php if($coupon_product){ ?>
			<?php foreach($coupon_product as $value){?>
<!--                --><?php //if($value->product->stockCount >0){ ?>
		<div class="flex-col mb5 br5 whitebg f12 bs coupon-product ml10 mr10" data-id="<?=$value->product->product_id?>" data-param="<?=$value->product->getPrice()?>">
			<div class="flex-item-4 tc pt5 pb5">
				<a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product->product_code,'shop_code'=>$value->product->store_code])?>"><img src="<?=\common\component\image\Image::resize($value->product->image,100,100)?>" alt="商品图片" width="95" height="95"></a>

            </div>

			<div class="flex-item-8 pt10">
				<a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product->product_code,'shop_code'=>$value->product->store_code])?>" class="f14"><?=$value->product->description->name?> <?php if($value->product->format){?>[<i class="format fb red"><?=$value->product->format?></i>]<?php }?></a>
                <p class="row-one red f13 mt5"><?php echo $value->product->description->meta_description?></p>
                <div class="pt10">

<!--					<div class="num-wrap num2 fr pr10 mt2 numDynamic ">-->
<!--						<span class="num-lower iconfont"  style="display:none;"></span>-->
<!--						<input type="text" value="0" class="num-text" style="display:none;">-->
<!--						<span class="num-add iconfont" style="display:none;"></span>-->
<!--                        <div class="add-click"><i class="iconfont"></i></div>-->
<!--					</div>-->
					<p>
						<span class="red f20 mr5 ">￥<?=$value->product->getPrice()?></span>
						<span class="gray9 del">￥<?=$value->product->price?></span>
					</p>
				</div>
			</div>
		</div>
<!--                --><?php //} ?>
			<?php } ?>
		<?php } ?>
	</div>
</section>

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

<?php $action = \yii\helpers\Url::to([Yii::$app->request->getUrl()]);?>
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
<?= $form->field($model, 'coupon_id',['template' => '{input}'])->hiddenInput(['id' => 'coupon_id'])?>
<?= $form->field($model, 'product_id',['template' => '{input}'])->hiddenInput(['id' => 'product_id'])?>
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
    <?= $form->field($model, 'address',['template' => '{label}<li>{input}</li>{error}'])->textarea(["placeholder" => '小区/写字楼/街道+楼号+楼层等','id'=>'address','class'=>'w f14 ','rows'=>2,'style'=>"height:45px;padding:5px;"])->label('详细地址')?>
    <?= $form->field($model, 'username', ['inputOptions' => ["placeholder" => '请填写收货人姓名']])->label('收货人') ?>
    <?= $form->field($model, 'telephone', ['inputOptions' => ["placeholder" => '请填写收货人电话号码']])->label('手机号') ?>
</ul>
<?php $store_id = 1;//家润?>
<?= h5\widgets\Checkout\Delivery::widget(['store_id' => $store_id]) ?>
<div class=" bdt  p10 w tc ">
    <a href="javascript:void(0)"  class='btn mbtn greenbtn w SubmitBtn'>提交订单 </a>
</div>
<?php ActiveForm::end(); ?>


<script type="text/html" id="goodlist_tpl">
	<div class=" w bdb  ellipsis "  style="overflow-x: auto;white-space: nowrap;">
		<% $.each(coupon_data,
		function(index, value)
		{ %>
		<img src="<%:=value.src%>" height="50" width="50" class="m5">
		<% }); %>
	</div>
</script>

<script>
<?php $this->beginBlock("JS_QQDiTu") ?>

var key = 515537;
var qty = 1;
$.post("<?=\yii\helpers\Url::to(['/coupon/ajax-cart-new'],true)?>",{'data':[{'id':key,'qty':qty}]},function(data){
    $.hideLoading();
    alert(data.status);
    if(!data.status){
        alert('false')
        return false;
    }else{
        alert('true')
    }
},'json');


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



