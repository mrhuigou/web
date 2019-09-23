<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title =Html::encode($this->title);
?>
    <style>
        .veiwport img{max-width: 32rem;height: auto;overflow: hidden;}
    </style>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
    <section class="veiwport  mb50">
            <?=Html::decode($model->description)?>
    </section>
<?=h5\widgets\MainMenu::widget();?>