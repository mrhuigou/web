<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model api\models\V1\ExpressCardView */

$this->title = '创建卡明细';
$this->params['breadcrumbs'][] = ['label' => '发货卡管理', 'url' => ['/express-card/index']];
$this->params['breadcrumbs'][] = ['label' => '卡明细管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
	<?=\backend\widgets\Customizer::widget();?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
				<?= Html::encode($this->title) ?>
            </h3>
			<?= \yii\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>


    <div class="express-card-view-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'card_view_count')->textInput(['maxlength' => true])->label("生成数量") ?>

        <?= $form->field($model, 'express_card_id')->dropDownList(ArrayHelper::merge(['0' => '请选择'], ArrayHelper::map(\api\models\V1\ExpressCard::find()->all(),'id', 'name')) )->label("所属提货卡") ?>

        <?php //echo $form->field($model, 'card_length')->textInput(['maxlength' => true])->label("卡号总长度"); ?>
        <?= $form->field($model, 'card_prefix')->textInput(['maxlength' => true])->label("卡号前缀") ?>

        <?= $form->field($model, 'pwd_length')->textInput(['maxlength' => true])->label("密码长度（最高8位）") ?>


        <?= $form->field($model, 'status')->dropDownList([0=>'未激活',1=>'已激活'] ) ?>



        <div class="form-group">
            <?= Html::submitButton( 'Create' , ['class' => 'btn btn-success' ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
