<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/8
 * Time: 11:55
 */
use \yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='申请团长';
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
	<section class="veiwport  pb50">
        <img src="/assets/images/share/banner.jpg" class="w">
		<div class="mt10  lh200 p10 fb tit--">申请单</div>
		<?php $form = ActiveForm::begin(['id' => 'form-address', 'fieldConfig' => [
			'template' => '<li><div class="t">{label}：</div><div class="c">{input}</div></li>{error}',
			'inputOptions' => ["class" => 'w f14'],
			'errorOptions' => ['class' => 'red pl5']
		],]); ?>
        <?= $form->field($model, 'province', ['template' => '{input}'])->hiddenInput(['id' => 'province'])->label(false) ?>
        <?= $form->field($model, 'city', ['template' => '{input}'])->hiddenInput(['id' => 'city'])->label(false) ?>
        <?= $form->field($model, 'district',['template' => '{input}'])->hiddenInput(['id' => 'district'])?>

        <ul class="line-book mt10">
            <?= $form->field($model, 'mode', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 'DOWN_LINE'=>'线下', 'ON_LINE'=>'线上'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],])?>
            <?= $form->field($model, 'type', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 'personal'=>'个人', 'company'=>'公司'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],])?>
            <?= $form->field($model, 'rebate_type', ['labelOptions' => ['class' => 'fb f14 p10 ']])->inline()->radioList([ 'order'=>'订单', 'product'=>'商品'], ['itemOptions' => ['labelOptions' => ['class' => 'radio-inline p10']],])?>
            <?= $form->field($model, 'name', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '请填写名称'])->label('名称'); ?>
            <?= $form->field($model, 'username', ['inputOptions' => ["placeholder" => '请填写展示名称']])->label('展示名称'); ?>
            <?= $form->field($model, 'description', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '请填写描述'])->label('描述'); ?>
            <?= $form->field($model, 'contact_name', ['labelOptions' => ['class' => 'pr5 ']])->textInput(['maxlength' => 255, 'placeholder' => '请填写联系人名称'])->label('联系人'); ?>
            <?= $form->field($model, 'telephone', ['inputOptions' => ["placeholder" => '请填写联系电话']])->label('联系电话'); ?>


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
            <?= $form->field($model, 'address',['template' => '{label}<li>{input}</li>{error}'])->textarea(["placeholder" => '小区/写字楼/街道+楼号+楼层等','id'=>'address','class'=>'w f14 ','rows'=>2,'style'=>"height:45px;padding:5px;"])?>

            <?php $url =  \yii\helpers\Url::to(["/user-share/xieyi"])?>
		<?= $form->field($model, 'agree')->checkbox(['template' => "<div class='p5'>{input} {label}<a href='{$url}'class='blue'>《每日惠购合伙人分享协议》</a></div>{error}"]) ?>
		</ul>
		<div class=" bdt  p10 w tc ">
			<?= Html::submitButton('提交', ['class' => 'btn mbtn greenbtn w', 'name' => 'save-button']) ?>
		</div>
		<?php ActiveForm::end(); ?>
        <div class="tc lh37   f14 tit-- ">
            加入团长的理由
        </div>
        <div class="p10 f14 lh150 pb20">
            <ul>
                <li>1、在“分享经济”模式下，分享快乐，分享收益；</li>
                <li>2、一次邀请，可获粉丝全年订单收益；</li>
                <li>3、完善的物流配送体系，全方位售后服务保障。</li>
                <li>一个平台，一种模式，一个梦想，一起增值</li>
            </ul>
        </div>
	</section>
	<!--浮动购物车-->
<?//=fx\widgets\MainMenu::widget();?>

<script>
    <?php $this->beginBlock("JS_QQDiTu") ?>
    /*地址选择*/
    $("#start").cityPicker({
        title: "选择地址",
        onChange: function (picker, values, displayValues) {
            $("#province").val(displayValues[0]);
            $("#city").val(displayValues[1]);
            $("#district").val(displayValues[2]);

        }
    });
    <?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['JS_QQDiTu'], \yii\web\View::POS_READY);
$this->registerJsFile("/assets/script/jqweui-picker.js",['depends'=>\h5\assets\AppAsset::className()]);
$this->registerJsFile("/assets/script/jqweui-city-picker.js",['depends'=>\h5\assets\AppAsset::className()]);

?>
