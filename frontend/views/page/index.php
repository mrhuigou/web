<?php
$this->title =  $page->description->title;
?>
<div  class="bc tc">
	<?=\yii\helpers\Html::decode($page->description->description)?>
</div>
<?php
$this->registerJsFile("/assets/js/template.js",['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("/assets/script/jq.min.js",['position' => \yii\web\View::POS_HEAD]);
?>