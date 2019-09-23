<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/29
 * Time: 11:57
 */
$this->registerJsFile("/assets/js/app/Base64.js",['depends'=>[\h5\assets\AppAsset::className()]]);
$this->registerJsFile("/assets/js/app/zhqd.min.js",['depends'=>[\h5\assets\AppAsset::className()]]);
?>
<?php
$this->beginBlock('JS_INIT')
?>
app.login();
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_INIT'],\yii\web\View::POS_END);
?>

