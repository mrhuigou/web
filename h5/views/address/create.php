<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='创建新地址';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<?= $this->render('_form', [
    'model' => $model,
    //'all_range' => $all_range
]) ?>