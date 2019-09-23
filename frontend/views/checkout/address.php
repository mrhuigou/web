<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/22
 * Time: 16:18
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
$this->context->layout = "main-iframe";
?>
    <style>
        .autocompleter {
            width: 480px;
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
    <div class="whitebg w500 f14 clearfix" id="address_action">
        <div class=" p10 ">
            <?php $form = ActiveForm::begin(['id' => 'form-address', 'fieldConfig' => [
                'template' => "<dl><dt>{label}</dt><dd>{input}</dd></dl>{error}",
                'inputOptions' => ["class" => 'input minput w'],
                'errorOptions' => ['class' => 'red mt5 mb5 db']
            ],]); ?>
            <?= $form->field($model, 'address')->textarea(['id' => 'address','placeholder' => '详细地址（小区/写字楼/街道+楼号+楼层等）','rows'=>2,'style'=>"height:45px;"]) ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'telephone') ?>
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
        <?php if ($model->has_other_zone) { ?>
            <p class=" p5 lh130 whitebg bd-red"> 配送区域仅限：<span class="red">市南区、市北区、李沧区、四方区、崂山区（部分）、黄岛区（）</span>。其它区域暂时尚未开通，敬请谅解。
            </p>
        <?php } else { ?>
            <p class=" p5 lh130 whitebg bd-red"> 配送区域仅限：<span class="red">市南区、市北区、崂山区(部分)、李沧区、四方区</span>。其它区域暂时尚未开通，敬请谅解。
            </p>
        <?php } ?>
    </div>
<?php $this->beginBlock("JS_Block") ?>
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
    }
    });
    $('#form-address').on('beforeSubmit', function (e) {
    var $form = $(this);
    $("#save_address_btn").attr('disabled','disabled');
    $.ajax({
    url: $form.attr("action"),
    type: 'post',
    dataType: 'json',
    data:$form.serialize(),
    success: function(data) {
    if(data.success){
    layer.closeAll();
    location.href="/checkout/index";
    }else{
    $("#save_address_btn").removeAttr('disabled');
    layer.msg('地址不合法！');
    }
    }
    });
    }).on('submit', function (e) {
    e.preventDefault();
    });
<?php $this->endBlock() ?>
<?php
$this->registerJsFile("http://map.qq.com/api/js?v=2.exp", ['depends' => \yii\web\YiiAsset::className()]);
$this->registerJsFile("/assets/script/autocompleter/jquery.autocompleter.min.js", ['depends' => \yii\web\YiiAsset::className()]);
$this->registerJs($this->blocks['JS_Block'], \yii\web\View::POS_READY);
