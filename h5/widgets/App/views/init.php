<?php
$this->beginBlock('JS_INIT')
?>

<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_INIT'],\yii\web\View::POS_END);
?>
