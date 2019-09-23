<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use api\models\V1\OrderStatus;

/* @var $this yii\web\View */
/* @var $model api\models\V1\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search ">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => ['class' => 'form-horizontal'],
		'fieldConfig' => [
			'options' => ['class' => 'col-md-6']
		],
	]); ?>

	<?php echo $form->field($model, 'order_id') ?>
	<?php echo $form->field($model, 'order_type_code')->dropDownList(ArrayHelper::merge(['0' => '全部'], ArrayHelper::map(\api\models\V1\OrderType::find()->orderBy('sort_order')->all(), 'code', 'name'))); ?>
	<?php echo $form->field($model, 'order_no') ?>
	<?php echo $form->field($model, 'customer_id') ?>
	<?php echo $form->field($model, 'telephone') ?>
	<?php echo $form->field($model, 'email') ?>
	<?php echo $form->field($model, 'shipping_username') ?>
	<?php echo $form->field($model, 'shipping_telephone') ?>
	<?php echo $form->field($model, 'payment_deal_no') ?>
	<?php echo $form->field($model, 'payment_method') ?>
	<?php echo $form->field($model, 'begin_date')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
		'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]);
	?>
	<?php echo $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
		'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]);
	?>
	<?php echo $form->field($model, 'begin_price'); ?>
	<?php echo $form->field($model, 'end_price'); ?>
	<?= $form->field($model, 'order_status_id')->dropDownList(ArrayHelper::merge(['0' => '全部'], ArrayHelper::map(OrderStatus::find()->all(), 'order_status_id', 'name'))); ?>
    <?php echo $form->field($model, 'sent_to_erp')->dropDownList(['' => '全部', 'Y' => '同步', 'N' => '未同步']) ?>
    <?= $form->field($model, 'affiliate_id')->dropDownList(ArrayHelper::merge(['0' => '全部'], ArrayHelper::map(\api\models\V1\Affiliate::find()->all(), 'affiliate_id', 'username'))); ?>
   <div class="col-md-6">
       <div class="btn-group">
           <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
           <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
       </div>
   </div>


	<?php ActiveForm::end(); ?>

</div>

