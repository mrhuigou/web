<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\WeixinMenu */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-12">
        <div class="note note-info">
            <h4 class="block">配置注意事项</h4>
            <p>
                1、自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。<br/>
                2、一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。<br/>
                3、创建自定义菜单后，由于微信客户端缓存，需要24小时微信客户端才会展现出来。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。
            </p>
        </div>

    </div>
</div>
<div class="weixin-menu-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->radioList([
        '0'=>'无类型',
        'click'=>'点击推事件',
        'view'=>'跳转URL',
        'scancode_push'=>'扫码推事件',
        'scancode_waitmsg'=>'扫码推事件且弹出“消息接收中”提示框',
        'pic_sysphoto'=>'弹出系统拍照发图',
        'pic_photo_or_album'=>'弹出拍照或者相册发图',
        'pic_weixin'=>'弹出微信相册发图器',
        'location_select'=>'弹出地理位置选择器',
        'media_id'=>'下发消息（除文本消息）',
        'view_limited'=>'跳转图文消息URL',
    ]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 125]) ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'key')->textInput(['maxlength' => 125]) ?>
    <?= $form->field($model, 'pid')->dropDownList(\yii\helpers\ArrayHelper::merge(['0'=>'顶级菜单'],\yii\helpers\ArrayHelper::map(\api\models\V1\WeixinMenu::find()->all(),'id','name')))?>
    <?= $form->field($model, 'media_id')->textInput() ?>
    <?= $form->field($model, 'sort')->textInput() ?>
    <?= $form->field($model, 'status')->radioList([0=>"关闭",1=>"启用"]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
