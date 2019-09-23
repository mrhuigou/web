<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title =$model->activity->title;
?>
<header class="header w" id="header">
    <a href="javascript:history.back();" class="his-back">返回</a>
    <h2 class="tc f18">申请报名</h2>
</header>
<style>
    .radio-inline,.checkbox-inline{
        background-color: #fff;
        border: 1px solid #eee;
        border-radius: 5px;
        font-size: 14px;
        height: 40px;
        line-height: 40px;
        padding-left: 10px;
        padding-right: 10px;
        text-align: left;
        margin:5px 0px;
        display: inline-block;
        width: 46%;
    }
</style>
<section class="veiwport">
    <div class="m10">
    <?php $form = ActiveForm::begin(['id' => 'login-form','fieldConfig' => [
        'inputOptions' => ['class' => 'appbtn tl w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'red']
    ],  ]); ?>
    <?=$form->field($model,'item')->hiddenInput(['id'=>'activity_item'])?>
    <?php if($model->activity->items){  ?>
       <?php foreach($model->activity->items as $item){ ?>
            <div class="activity_items cp w p10 br5 mb5 <?=$item->id==$model->item?'bluebg':'whitebg'?>" data-content="<?=$item->id?>">
               <h1 class="fb"><?=$item->name?>  <em class="red">￥<?=$item->fee?></em></h1>
                <p class="bdt-d gray">
                    <?=$item->quantity?"限".$item->quantity."人":"不限人数"?>
                </p>
            </div>
        <?php }?>
        <?php }else{ ?>
        <div class="activity_items cp w p10 br5 mb5 whitebg" data-content="0">
            <h1 class="fb"><?=$model->activity->fee==0?"免费":($model->activity->fee==2?"AA制":"收费")?></h1>
            <p class="bdt-d gray">
                <?=$model->activity->quantity?"限".$model->activity->quantity."人":"不限人数"?>
            </p>
        </div>
        <?php } ?>
    <?=$form->field($model,'quantity')?>
    <?=$form->field($model,'username')?>
    <?=$form->field($model,'telephone')?>
    <?php if($model->activity->option){
        foreach($model->activity->option as $option) { ?>
            <?php if($option->type=='input'){ ?>
                <?= $form->field($model, 'option_data['.$option->id.']')->label($option->name) ?>
            <?php }elseif($option->type=='radio'){ ?>
                <?= $form->field($model, 'option_data['.$option->id.']')->inline(true)->radioList(\yii\helpers\ArrayHelper::map($option->optionValue,'name', 'name'))->label($option->name) ?>
            <?php }elseif($option->type=='checkbox'){ ?>
                <?= $form->field($model, 'option_data['.$option->id.']')->inline(true)->checkboxList(\yii\helpers\ArrayHelper::map($option->optionValue,'name', 'name'))->label($option->name) ?>
            <?php } ?>
            <?php
        }
    } ?>
        <?php if($model->activity->activitykv){
            foreach($model->activity->activitykv  as $activitykv) { ?>
                <?php if($activitykv->type=='input'){ ?>
                    <?= $form->field($model, 'activitykv_datas['.$activitykv->club_activity_kv_id.']')->label($activitykv->title) ?>
               <?php } ?>
                <?php
            }
        } ?>
    <?=$form->field($model,'remark')?>
    <?= Html::submitButton('提交报名', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'login-button']) ?>
    <?php ActiveForm::end(); ?>
    </div>
</section>
<?php $this->beginBlock('JS_END') ?>
$(".activity_items").click(function(){
$(this).siblings(".activity_items").removeClass('bluebg').addClass("whitebg");
$(this).removeClass('whitebg').addClass("bluebg");
$("#activity_item").val($(this).attr('data-content'));
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
