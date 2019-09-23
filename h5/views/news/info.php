<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title =$model->description->title;
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
    <?=Html::decode($model->description->description)?>
</section>
<?=h5\widgets\MainMenu::widget();?>