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
$this->title ='申请合伙人';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
	<section class="veiwport  pb50">
        <img src="/assets/images/share/banner.jpg" class="w">
		<div class="mt10  lh200 p10 fb tit--">申请单</div>
		<?php $form = ActiveForm::begin(['id' => 'form-address', 'fieldConfig' => [
			'template' => '<li><div class="t">{label}：</div><div class="c">{input}</div></li>{error}',
			'inputOptions' => ["class" => 'w f14'],
			'errorOptions' => ['class' => 'red pl5']
		],]); ?>
		<ul class="line-book mt10">
		<?= $form->field($model, 'username', ['inputOptions' => ["placeholder" => '请填写收货人姓名']]) ?>
		<?= $form->field($model, 'telephone', ['inputOptions' => ["placeholder" => '请填写收货人电话号码']]) ?>
        <?php $url =  \yii\helpers\Url::to(["/user-share/xieyi"])?>
		<?= $form->field($model, 'agree')->checkbox(['template' => "<div class='p5'>{input} {label}<a href='{$url}'class='blue'>《家润合伙人分享协议》</a></div>{error}"]) ?>
		</ul>
		<div class=" bdt  p10 w tc ">
			<?= Html::submitButton('提交', ['class' => 'btn mbtn greenbtn w', 'name' => 'save-button']) ?>
		</div>
		<?php ActiveForm::end(); ?>
        <div class="tc lh37   f14 tit-- ">
            加入合伙人的理由
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
<?=h5\widgets\MainMenu::widget();?>