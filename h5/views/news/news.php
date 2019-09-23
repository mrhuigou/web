<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = $model->title;
?>

<?=h5\widgets\Header::widget(['title'=>$this->title])?>
    <style>
        .veiwport img{max-width: 28rem;height: auto;overflow: hidden;}
    </style>
	<section class="veiwport bd  mb50 whitebg">
        <div class="m10 clearfix">
	       <?= Html::decode($model->description) ?>
        </div>
	</section>
<?= h5\widgets\MainMenu::widget(); ?>