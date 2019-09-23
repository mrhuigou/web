<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>


    <div  class="w990 bc pt50">
        <div class="steps">
            <ol>
                <li class="active"><i>1</i><span>填写注册信息</span></li>
                <li><i>2</i><span>验证身份</span></li>
                <li><i>3</i><span>注册成功</span></li>
            </ol>
        </div>
        <div class="reg-box bc">  
            <div class="mt15 bc w400 ">
                <ul class="tag-tit w tc clearfix mb10" style="display: block;">
                    <li class=" w200 <?=Yii::$app->request->get('type')?"":'current'?>" style="padding: 0;"><a href="<?=\yii\helpers\Url::to(['/site/signup'])?>">手机注册</a></li>
                    <li  class="w200 <?=Yii::$app->request->get('type')?"current":''?>" style="padding: 0;"><a href="<?=\yii\helpers\Url::to(['/site/signup','type'=>'email'])?>">邮箱注册</a></li>
                </ul>
                <div class="tl w">
                    <?php if(!Yii::$app->request->get('type')){ ?>
                        <?php $form = ActiveForm::begin(['fieldConfig'=>['options'=>['class'=>'p5 clearfix '],'labelOptions'=>['class'=>'db fb w tl '],'errorOptions'=>['tag'=>'p']]]); ?>
                        <?= $form->field($model, 'telephone',['inputOptions' =>['class' => 'input linput placeholder w telephone bd',]]) ?>
                        <?= $form->field($model, 'verifycode',['template' => '{label}{input}{error}<div><button type="button"  id="send-vcode" class="btn w lbtn db " style="height: 41px;">获取语音验证码</button></div>',])->widget(Captcha::className(), [ "options"=>['class' => 'input linput db bd placeholder fl verifycode','style'=>'width:50%'],
                            "imageOptions"=>["style"=>"width:45%;height:41px;",'class'=>'cp'],
                            'template' => '{input}{image}',
                        ]) ?>
                        <?= $form->field($model, 'checkcode',['inputOptions' =>['class' => 'input linput db bd placeholder w checkcode'],'template' => "{label}{input}\n{hint}\n{error}"]) ?>
                        <p class="red lh150 p5">家润慧生活通过语音播报验证码,请你接听来自0532/400/12590开头的来电.</p>
                        <?= $form->field($model, 'password',['inputOptions' =>['class' => 'input linput bd w']])->passwordInput() ?>
                        <?= $form->field($model, 'password_repeat',['inputOptions' =>['class' => 'input linput bd w']])->passwordInput() ?>

                       <div class="p5 tc"><button type="submit"  class="btn lbtn greenbtn w">确认注册</button></div>
                        <?php ActiveForm::end(); ?>
                    <?php }else{ ?>
                        <?php $form = ActiveForm::begin(['fieldConfig'=>['options'=>['class'=>'p5 clearfix'],'labelOptions'=>['class'=>'db fb w tl '],'errorOptions'=>['tag'=>'p'],'inputOptions' =>['class' => 'input linput bd w']]]); ?>
                        <?= $form->field($model, 'email') ?>
                        <?= $form->field($model, 'password')->passwordInput()?>
                        <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                        <?= $form->field($model, 'verifycode',['template' => "{label}{input}{error}",])->widget(Captcha::className(), [ "options"=>['class' => 'input linput placeholder fl bd db','errorOptions'=>['tag'=>'p']],
                            "imageOptions"=>["style"=>"width:45%;height:41px;"],
                            'template' => '{input}{image}',
                        ]) ?>
                        <div class="p5 tc"><button type="submit" class="btn lbtn greenbtn w">确认注册</button></div>
                        <?php ActiveForm::end(); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        
    </div>
    
    <div id="footer"></div>
    <?php $this->beginBlock("JS_Block")?>
        $("#send-vcode").click(function(){
            var _this=$(this);
            var telephone = $("#signupform-telephone");
            var verifycode=$("#signupform-verifycode");
            $.post('/site/sendcode',{'telephone':telephone.val(),'verifycode':verifycode.val()},function(data){
                if(data.status==0){
                $.each(data.message,function(name,value){
                if($.inArray(name, ['telephone','verifycode'])>=0){
                var _parent=$("#signupform-"+name).parent(".required");
                _parent.removeClass("has-success").addClass("has-error");
                _parent.find(".help-block-error").html(value);
                }else{
                 alert(value);
                }
                });
                $("#signupform-verifycode-image").trigger('click');
                }else{
                time(_this);
                }
            },'json');
        });
<?php $this->endBlock()?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_READY);
?>
