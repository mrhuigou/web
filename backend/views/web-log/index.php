<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '页面流量查询';
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
                控制台 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

    <div class="page-index">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <?php echo $form->field($searchModel, 'url')->label("查询链接Url") ?>
        <?php echo $form->field($searchModel, 'begin_date')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
            'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]);
        ?>
        <?php echo $form->field($searchModel, 'end_date')->widget(\yii\jui\DatePicker::className(), ['language' => 'zh-CN',
            'dateFormat' => 'yyyy-MM-dd','options'=>['class'=>'form-control']]);
        ?>

        <div>
            <h3 class="page-title">
                <small>点击量：
                    <?php echo $count?></small>
            </h3>

        </div>

        <div class="btn-group">
            <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

</div>
