<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = '导入商品信息';
$this->params['breadcrumbs'][] = ['label' => '发货卡管理', 'url' => ['/express-card/index']];
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

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput(['class'=>'btn-file file-upload'])->label("请选择excel文件") ?>


    <!--<input type="hidden" name="type" value="--><?php //echo Yii::$app->request->get('type');?><!--">-->
    <input type="hidden" name="express_card_id" value="<?php echo Yii::$app->request->get('express_card_id');?>">

    <button type="submit" class="btn green">确认</button>

<?php ActiveForm::end() ?>
</div>
