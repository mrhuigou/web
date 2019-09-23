<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(['NEWS'=>'消息类型','URL'=>'链接类型'])->label("类型选择") ?>
    <?= $form->field($model, 'device')->dropDownList(['H5'=>'H5','PC'=>'PC','ALL'=>'ALL'])->label("设备选择") ?>

    <?= $form->field($model, 'title')->textInput()->label("标题必填") ?>
    <?= $form->field($model, 'intro')->textInput()->label("简介") ?>
    <?= $form->field($model, 'image',['options'=>['class'=>'']])->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/weixin-message/fileapi-upload']
            ]
        ]
    )->label('图片') ?>

    <?= $form->field($model, 'link',['options'=>['class'=>'url-link']])->textInput()->label('链接（Http://xxx.xxx.com）'); ?>

<br>

<div style="border:1px solid #e5e5e5;padding:10px 5px;margin-bottom: 20px">


    <?= $form->field($model, 'filter_type')->dropDownList(['none'=>'不推送给任何人', 'single_customer'=>'推送至单一客户','buy_product'=>'购买过某商品','all'=>'所有客户'])->label('消息发送对象') ?>

    <?= $form->field($model, 'filter_textfield',['options'=>['class'=>'filter-textfield filter']])->textInput()->label("条件内容（客户电话或者商品base_code）") ?>

    <?php  echo  $form->field($model, 'filter_date_begin',['options'=>['class'=>'filter filter_order_date']])->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("订单开始时间");?>
    <?php  echo  $form->field($model, 'filter_date_end',['options'=>['class'=>'filter filter_order_date']])->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("订单结束时间");?>

</div>

    <div class="form-group">
        <?= Html::submitButton( '保存', ['class' =>  'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->beginBlock('JS_END') ?>


	var pack_group = $(".pack-items");
    var url_group = $(".url-link");
    //var news_group = $(".news-description");

	if($("#messagecontentform-type").val() == 'PACK'){
        pack_group.show();
        url_group.hide();
        //news_group.hide();
	}
    if($("#messagecontentform-type").val() == 'URL'){
        pack_group.hide();
        url_group.show();
        //news_group.hide();
    }
    if($("#messagecontentform-type").val() == 'NEWS'){
        pack_group.hide();
        url_group.hide();
        //news_group.show();
    }
	$("#messagecontentform-type").on("change",function(){
		if($("#messagecontentform-type").val() == 'PACK'){
            pack_group.show();
            url_group.hide();
            //news_group.hide();
		}else if($("#messagecontentform-type").val() == 'URL') {
             pack_group.hide();
            url_group.show();
            //news_group.hide();
		}else if($("#messagecontentform-type").val() == 'NEWS') {
            pack_group.hide();
            url_group.hide();
            //news_group.show();
        }
	});


var filter = $(".filter");
var filter_textfield = $('.filter-textfield');
    var filt_date = $('.filter_order_date');
    filter.hide();
    $("#messagecontentform-filter_type").on("change",function(){

        var filter_type = $("#messagecontentform-filter_type").val();
        if( filter_type== 'all' || filter_type == 'none'){
             filter.hide();
        }else if( filter_type == 'single_customer') {
    filter_textfield.show();
        }else if(filter_type == 'buy_product') {
            filter.show();
        }
    });

<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>