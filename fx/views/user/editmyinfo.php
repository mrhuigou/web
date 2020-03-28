<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title ='个人资料';
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2><?= Html::encode($this->title) ?></h2>
    <a href="<?php echo \yii\helpers\Url::to(['/user/index'])?>" class="header-cate iconfont">&#xe603;</a>
</header>
<style>
    .radio{
        display: inline;
        margin-right: 10px;
    }
</style>
<section class="veiwport">

        <div class="w tc">
            <a  href="<?=\yii\helpers\Url::to(['/user/avatar'])?>">
                <img src="<?=\common\component\image\Image::resize($model->photo,100,100)?>" class="img-circle" width="100" height="100">
            </a>
        </div>
    
    <div class="p10">
    <?php $form = ActiveForm::begin(['id' => 'form-myinfo','fieldConfig' => [
        'template' => "<div class=\"mt10 mb10 clearfix\">{label}{input}</div>{error}",
        'inputOptions' => ["class"=>'appbtn tl w'],
        'labelOptions'=>['class'=>'fb'],
        'errorOptions'=>['class'=>'red mt5 mb5 db']
    ],  ]);?>
    <?= $form->field($model, 'nickname')->textInput(["placeholder"=>'昵称'])->label("昵称"); ?>
	<?= $form->field($model, 'signature')->textInput(["placeholder"=>'个性签名'])->label("个性签名"); ?>
    <?= $form->field($model, 'gender')->radioList(["男"=>'男','女'=>'女','未知'=>'保密'],['class'=>'w appbtn tl','itemOptions'=>['labelOptions'=>['class'=>'fl']]])->label("性别"); ?>
    <?= $form->field($model, 'education',[ 'inputOptions' => ["class"=>'w appbtn']])->dropDownList([
        '请选择'=> '请选择','小学'=>'小学','中学'=>'中学','高中'=>'高中','中专'=>'中专','大专'=>'大专','本科'=>'本科','研究生'=>'研究生','博士及以上'=>'博士及以上'])->label("学历") ?>
    <?= $form->field($model, 'birthday')->widget(\yii\widgets\MaskedInput::className(), [ 'mask' => '9999-99-99',])->textInput(["placeholder"=>'生日'])->label("生日"); ?>

    <?= $form->field($model, 'district_id',['template'=>'<div class="mb10 clearfix"><div class="fl pw32 appbtn pmr1">山东省</div><div class="fl pw32 appbtn">青岛市</div>{input}</div>{error}', 'inputOptions' => ["class"=>'fr pw32 appbtn'],])->dropDownList([
        0=>'请选择',7607=>'市南区',7608=> '市北区',9390=>'李沧区',7611=>'崂山区',7609=>'四方区'])->label("地址") ?>


        <div class="m5">
     <?= Html::submitButton('保存', ['class' => 'btn lbtn greenbtn w  ', 'name' => 'save-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
        <div class="m5 mt10">
            <a href="<?php echo \yii\helpers\Url::to(['/site/logout'])?>" class="btn lbtn w redbtn " >退出账户</a>
        </div>
    </div>
</section>

