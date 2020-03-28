<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/9
 * Time: 16:55
 */
$this->registerJsFile("/assets/js/app/Base64.js", ['depends' => [\h5\assets\AppAsset::className()]]);
$this->registerJsFile("/assets/js/app/zhqd.min.js", ['depends' => [\h5\assets\AppAsset::className()]]);
$this->beginBlock('JS_INIT')
?>
 /*微信分享*/
$(".share-guide").click(function(){
<?php if ($data) { ?>
    app.Share('<?= $data['link'] ?>','<?= $data['title'] ?>','<?= $data['desc'] ?>','<?= $data['image'] ?>');
<?php } ?>
    });
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_INIT'], \yii\web\View::POS_END);
?>