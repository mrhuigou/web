<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='申请成为分销商';
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
<?= $this->render('_form', [
    'model' => $model,
    //'all_range' => $all_range
]) ?>