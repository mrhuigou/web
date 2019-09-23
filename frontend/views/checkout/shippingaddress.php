<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
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
<div class="address_add w570">
    <h2 class="titStyle1 f14 gray6 popTitle"><a href="javascript:void(0)" class="close_btn fr mt10 mr10"></a><i></i></h2>
    <div class="whitebg p20 pl50 f14">
        <?php $form = ActiveForm::begin(['id' => 'form-address', 'fieldConfig' => [
            'template' => "<dl><dt>{label}</dt><dd>{input}</dd></dl>{error}",
            'inputOptions' => ["class" => 'input minput w'],
            'errorOptions' => ['class' => 'red mt5 mb5 db']
        ],]); ?>
        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'telephone') ?>
        <?= $form->field($model, 'poiname')->textInput(['id' => 'poi_search','placeholder' => '检索您所在的小区/写字楼/学校街道']) ?>
        <?= $form->field($model, 'poiaddress')->textInput(['id' => 'poiaddress','placeholder' => '街道地址（如市南区山东路10号）']) ?>
        <?= $form->field($model, 'address')->textInput(['placeholder' => '详细地址（门牌号/楼层等）']) ?>
        <?= $form->field($model, 'postcode') ?>
        <?= $form->field($model, 'is_default')->checkbox() ?>
        <?= Html::submitButton('提交', ['class' => 'btn mbtn w greenbtn ', 'name' => 'save-button']) ?>
        <?= $form->field($model, 'lat', ['template' => '{input}'])->hiddenInput(['id' => 'address-lat']) ?>
        <?= $form->field($model, 'lng', ['template' => '{input}'])->hiddenInput(['id' => 'address-lng']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
