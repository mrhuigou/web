<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
$this->title ='编辑新地址';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
<div class="w tc p30">
    <a class="aui-icon aui-icon-delete img-circle  gray9 whitebg p10" href="<?=\yii\helpers\Url::to(['/invoice/delete','id'=>$model->invoice_id,'redirect'=>\Yii::$app->request->get('redirect')])?>" data-method="post" data-confirm="确认删除当前发票吗？"></a>
</div>

