<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>
	<style>
		.autocompleter {
			width: 500px;
			display: none;
		}

		.autocompleter-show {
			display: block;
		}

		.autocompleter,
		.autocompleter-hint {
			position: absolute;
		}

		.autocompleter-list {
			background-color: #ffffff;
			max-height: 200px;
			overflow-y: scroll;
			border: 1px solid #eeeeee;
			list-style: none;
			margin: 0;
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
		}

		.autocompleter-item {
			cursor: pointer;
			padding: 0 5px;
			border-bottom: 1px solid #eee;
		}

		.autocompleter-item:hover {
			/* Hover State */
			background-color: #f5f5f5;
		}

		.autocompleter-item-selected {
			/* Selected State */
		}

		.autocompleter-item strong {
			/* Highlight Matches */
			color: red;
		}

		.autocompleter-hint {
			width: 100%;
			display: none;

			/** Need absolute position over input text */
		}

		.autocompleter-hint-show {
			display: block;
		}

		.autocompleter-hint span {
			color: transparent;
		}

	</style>
	<div class="whitebg  clearfix" id="address_action">
		<h2 class="f16 p15 m5  bdb">收货地址</h2>
		<?php if ($model->has_other_zone) { ?>
			<p class=" p5 lh130 whitebg bd-red"> 配送区域仅限：<span class="red">市南区、市北区、崂山区、李沧区、四方区、城阳区、黄岛区</span>。其它区域暂时尚未开通，敬请谅解。
			</p>
		<?php } else { ?>
			<p class=" p5 lh130 whitebg bd-red"> 配送区域仅限：<span class="red">市南区、市北区、崂山区、李沧区、四方区</span>。其它区域暂时尚未开通，敬请谅解。
			</p>
		<?php } ?>
		<div class=" fl w500 p10 f14">
			<?php $form = ActiveForm::begin(['id' => 'form-address', 'fieldConfig' => [
				'template' => "<dl><dt>{label}</dt><dd>{input}</dd></dl>{error}",
				'inputOptions' => ["class" => 'input minput w'],
				'errorOptions' => ['class' => 'red mt5 mb5 db']
			],]); ?>
			<?= $form->field($model, 'username') ?>
			<?= $form->field($model, 'telephone') ?>
			<?= $form->field($model, 'address')->textarea(['id' => 'address','placeholder' => '详细地址（小区/写字楼/街道+楼号+楼层等）','rows'=>2,'style'=>"height:45px;"]) ?>
			<?= $form->field($model, 'is_default')->checkbox() ?>
			<?= Html::submitButton('提交', ['class' => 'btn mbtn w greenbtn ', 'name' => 'save-button']) ?>
			<?= $form->field($model, 'postcode',['template' => '{input}'])->hiddenInput() ?>
			<?= $form->field($model, 'poiname',['template' => '{input}'])->hiddenInput(['id' => 'poiname']) ?>
			<?= $form->field($model, 'poiaddress',['template' => '{input}'])->hiddenInput(['id' => 'poiaddress']) ?>
			<?= $form->field($model, 'province', ['template' => '{input}'])->hiddenInput(['id' => 'province']) ?>
			<?= $form->field($model, 'city', ['template' => '{input}'])->hiddenInput(['id' => 'city']) ?>
			<?= $form->field($model, 'district', ['template' => '{input}'])->hiddenInput(['id' => 'district']) ?>
			<?= $form->field($model, 'lat', ['template' => '{input}'])->hiddenInput(['id' => 'address-lat']) ?>
			<?= $form->field($model, 'lng', ['template' => '{input}'])->hiddenInput(['id' => 'address-lng']) ?>
			<?php ActiveForm::end(); ?>
		</div>
		<div class="fl w450 p10 graybg " id="container" style="height: 388px;"></div>
	</div>

<?php $this->beginBlock("JS_Block") ?>
	var citylocation,map,marker = null;
	var init=function() {
	var center = new qq.maps.LatLng(36.06712,120.38267);
	map = new qq.maps.Map(document.getElementById('container'),{
	center: center,
	zoom: 17
	});
	map.setOptions({
	keyboardShortcuts : false, //设置禁止通过键盘控制地图。默认情况下启用键盘快捷键。
	scrollwheel : false        //设置滚动缩放默认不允许
	});
	//调用城市服务信息
	citylocation = function(lat, lng){
	if(lat && lng){
	var latLng = new qq.maps.LatLng(lat, lng);
	map.setCenter(latLng);
	if (marker != null) {
	marker.setMap(null);
	}
	//设置marker标记
	marker = new qq.maps.Marker({
	map: map,
	draggable: true,
	position:latLng
	});
	//获取标记的点击事件
	qq.maps.event.addListener(marker, 'mouseup', function(eve) {
	citylocation(eve.latLng.lat,eve.latLng.lng);
	$("#address-lat").val(eve.latLng.lat);
	$("#address-lng").val(eve.latLng.lng);
	});
	}
	}
	}
	init();
	citylocation('<?=$model->lat?>','<?=$model->lng?>');
	$('#address').autocompleter({
	limit: 10,
	cache: false,
	highlightMatches:true,
	source: '<?= \yii\helpers\Url::to(['/address/suggestion']) ?>',
	template:'<h1 class="fb poi-name">{{ label }}</h1><p class="gray9 poi-address f12 " data-province="{{ province }}" data-city="{{ city }}" data-district="{{ district }}">{{ address }}</p><div class="loaction" data-lat="{{ lat }}" data-lng="{{ lng }}"></div>',
	callback: function (value, index) {
	var poiname=$(".autocompleter-item").eq(index).find(".poi-name").html();
	var address=$(".autocompleter-item").eq(index).find(".poi-address").html();
	var province=$(".autocompleter-item").eq(index).find(".poi-address").attr('data-province');
	var city=$(".autocompleter-item").eq(index).find(".poi-address").attr('data-city');
    var district=$(".autocompleter-item").eq(index).find(".poi-address").attr('data-district');
	var poiaddress=address.replace(province+city,'');
	var lat=$(".autocompleter-item").eq(index).find(".loaction").attr('data-lat');
	var lng=$(".autocompleter-item").eq(index).find(".loaction").attr('data-lng');
	$("#poiname").val(poiname);
	$("#poiaddress").val(poiaddress);
	$("#province").val(province);
	$("#city").val(city);
	$("#district").val(district);
	$("#address-lat").val(lat);
	$("#address-lng").val(lng);
	//设置经纬度信息
	citylocation(lat,lng);
	}
	});
<?php $this->endBlock() ?>

<?php
$this->registerJsFile("http://map.qq.com/api/js?v=2.exp", ['depends' => \yii\web\YiiAsset::className()]);
$this->registerJsFile("/assets/script/autocompleter/jquery.autocompleter.min.js", ['depends' => \yii\web\YiiAsset::className()]);
$this->registerJs($this->blocks['JS_Block'], \yii\web\View::POS_READY);