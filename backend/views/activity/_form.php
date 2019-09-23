<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use api\models\V1\ClubActivityCategory;
use common\extensions\widgets\fileapi\Widget as FileAPI;
/* @var $this yii\web\View */
/* @var $model api\models\V1\WeixinMessage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="weixin-message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'msgtype')->dropDownList(['text'=>'文本信息','news'=>'图文消息']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 64])->label("活动标题") ?>
    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 128])->label("副标题") ?>
     <?=$form->field($model, 'activity_category_id')->dropDownList(ArrayHelper::map(ClubActivityCategory::find()->all(),'activity_category_id', 'title'))->label('活动分类');?>

    <?= $form->field($model, 'customer_id',['template'=>'{input}'])->textInput(['type'=>'hidden'])->hiddenInput() ?>

    <?php // $form->field($model, 'descr')->textarea(['rows' => 6]) ;?>

    <?= $form->field($model, 'image')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/activity/fileapi-upload']
            ]
        ]
    )->label("活动主图") ?>

    <div class="row">
    <div class="col-sm-10">
        
    <?= $form->field($model, 'music')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/activity/fileapi-upload']
            ]
        ]
    )->label("背景音乐") ?>
    </div> 
    <div class="col-sm-2" style="margin-top: 25px;">
    <?= Html::button("删除音乐",['class'=>'btn btn-primary','id'=>'removemusic']);?>
    </div>
    </div>

    <?php  echo  $form->field($model, 'signup_end')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("报名截止时间");
    ?>
    <?php  echo  $form->field($model, 'begin_datetime')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("活动开始时间");
    ?>
    <?php  echo  $form->field($model, 'end_datetime')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '9999-99-99 99:99:99',
    ])->label("活动结束时间");
    ?>

    <?= $form->field($model, 'address')->textInput()->label("地址") ?>
    <?= $form->field($model, 'quantity')->textInput()->label("人数/数量") ?>

    <?= $form->field($model, 'fee')->radioList(['0'=>'免费','1'=>'收费','2'=>'AA制'],['class'=>'activity_fee_select'])->label("费用"); ?>
    <?php $count = 0;?>
    <div class="post_pay_item none" style="<?php if($model->items){ echo "display: block;";}?>">
        <div id="items_list">
        <?php foreach($model->items as $items){?>
            <div class="cost_content clearfix" id="item_<?php echo $count;?>">
                     <?= $form->field($items, 'id',['options'=>['class'=>"activity_items"],'template'=>'{input}'])->textInput(['name'=>'items['.$count.'][id]','type'=>'hidden']); ?>
                    <?= $form->field($items, 'name',['options'=>['class'=>"activity_items"]])->textInput(['name'=>'items['.$count.'][name]'])->label("费用名称"); ?>
                    <?= $form->field($items, 'fee',['options'=>['class'=>"activity_items"]])->textInput(['name'=>'items['.$count.'][fee]'])->label("费用"); ?>
                    <?= $form->field($items, 'quantity',['options'=>['class'=>"activity_items"]])->textInput(['name'=>'items['.$count.'][quantity]'])->label("限制人数"); ?>
                <button type="button" class="controlClear btn btn-primary" onclick="delItem(<?php echo $count;?>,<?php echo $items->id;?>)">删除</button>
            </div>
            <?php $count++;?>
        <?php }?>
        </div>
        <div class="cost_add" id="party_101_post_option_cost_add">
            <button type="button" class="btn btn-primary" onclick="addItem()" id="post_party_option_cost_add_icon">+增加电子票</button>
        </div>
    </div>
    <div id="post_form_join_items">
        <label for="clubactivity-quantity" class="control-label">报名填写信息</label>
        <ul class="post_form_join_item">
            <li id="useritem_0" class="join_item_static clearfix mt5"><div class="item_title form-control">姓名</div>默认必填<div class="help-block"></div></li>
            <li id="useritem_1"  class="join_item_static clearfix mt5" id="post_party_101_join_item_2"><div  class="item_title form-control">手机</div>默认必填<div class="help-block"></div></li>
            <?php $count2 = 2;?>
        <?php foreach($model->activitykv as $kv){?>
            <li id="useritem_<?php echo $count2;?>"  class="join_item_static clearfix mt5"><div class="item_title" for="<?php echo $kv->club_activity_kv_id?>">
                    <?= $form->field($kv, 'title',['options'=>['class'=>"activity_user_items"]])->textInput(['name'=>'userItems['.$count2.'][title]'])->label(false,['class'=>'none']); ?>
                    <?= $form->field($kv, 'club_activity_kv_id',['options'=>['class'=>"activity_user_items"]])->textInput(['name'=>'userItems['.$count2.'][id]','type'=>'hidden'])->label(false); ?>
                </div>
                <button type="button" class="controlClear btn btn-primary" onclick="delUserItem(<?php echo $count2;?>,<?php echo $kv->club_activity_kv_id;?>)">删除</button>
            </li>
            <?php $count2++;?>
        <?php }?>

        </ul>
        <div class="cost_add" id="party_101_post_option_cost_add">
            <button type="button" class="btn btn-primary" onclick="newUserItem()" id="post_party_option_cost_add_icon">+增加</button>
        </div>
    </div>
    <?= $form->field($model, 'status')->radioList(['0'=>'禁用','1'=>'启用'])->label("状态"); ?>
    <?= $form->field($model, 'description')->widget(\common\extensions\widgets\kindeditor\KindEditor::className(),
        ['clientOptions' => ['allowFileManager' => 'true','allowUpload' => 'true']])  ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php $this->beginBlock('JS_END') ?>
    var count = <?php echo $count;?>;
    var count2 = <?php echo $count2?>;
    function newUserItem(){
        var html = "";
        html += '<li class="join_item_static clearfix" id="useritem_'+count2+'"><div for="0" class="item_title">';
            html += '<div class="activity_user_items field-clubactivitykv-title">';
                html += '<input type="text" value="" name="userItems['+count2+'][title]" class="form-control" id="clubactivitykv-title">';
                html += '<input type="hidden" value="0" name="userItems['+count2+'][id]" class="form-control" id="clubactivitykv-title">';
                html += '<div class="help-block"></div>';
                html += '</div></div>';
        html += '<button onclick="delUserItem('+count2+',0)" class="controlClear btn btn-primary" type="button">删除</button>';
        html += '</li>';
        count2 = count2+1;
        $(".post_form_join_item").append(html);
    }
    function delUserItem(item,club_activity_kv_id){
        if(club_activity_kv_id != 0){
            if(confirm("删除后不能恢复，确认删除？")){
                $.post("<?php echo \yii\helpers\Url::to(['activity/del-user-item'])?>", { id: club_activity_kv_id },function(data){
                    if(data == 'success'){

                    }
                     $("#useritem_"+item).remove();
                } );

            }
        }else{
            $("#useritem_"+item).remove();
        }
    }
        function addItem(){
            var html = "";
            html += '<div class="cost_content clearfix" id="item_'+count+'">';
                html += '<div class="field-clubactivityitems-name activity_items">';
                    html += '<input type="hidden" class="form-control" name="items['+count+'][id]">';
                    html += '<label class="control-label" for="clubactivityitems-name">费用名称</label>';
                    html += '<input type="text" class="form-control" name="items['+count+'][name]" value="">';
                    html += '<div class="help-block"></div>';
                    html += '</div>';
                html += '<div class="field-clubactivityitems-fee activity_items">';
                    html += '<label class="control-label" for="clubactivityitems-fee">费用</label>';
                    html += ' <input type="text" class="form-control" name="items['+count+'][fee]" value="">';
                    html += ' <div class="help-block"></div>';
                    html += '</div>';
                html += ' <div class="field-clubactivityitems-quantity activity_items">';
                    html += '<label class="control-label" for="clubactivityitems-quantity">限制人数</label>';
                    html += '<input type="text" class="form-control" name="items['+count+'][quantity]" value="">';
                    html += ' <div class="help-block"></div>';
                    html += ' </div>';
        html += '<label class="control-label" for="clubtrycoupon-action">操作</label>';
        html += '<div class="del"><button type="button" title="清空" class="controlClear btn btn-primary" onclick="delItem('+count+',0)">删除</button></div>';
                html += '</div>';
            count = count+1;
            $("#items_list").append(html);
        }
    function delItem(item,item_id){
        if(item_id != 0){
            if(confirm("删除后不能恢复，确认删除？")){
                 $.post("<?php echo \yii\helpers\Url::to(['activity/del-item'])?>", { id: item_id },function(data){
                    if(data == 'success'){

                    }
                     $("#item_"+item).remove();
                } );
            }
        }else{
            $("#item_"+item).remove();
        }


    }
        $(".activity_fee_select input[type='radio']").click(function(){
            var _this = $(this);
            if(_this.val() == 1){
                $(".post_pay_item").show();
            }else{
                 $(".post_pay_item").hide();
            }
        })

    $("#removemusic").on('click',function(){
        $("#uploader-clubactivity-music span[data-fileapi='browse-text']").removeClass('hidden');
        $("#uploader-clubactivity-music span[data-fileapi='name']").text('');
        $("input#clubactivity-music").val('');
    });
    <?php $this->endBlock() ?>
    <?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
    ?>

</div>
