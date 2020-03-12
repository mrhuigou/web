<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '用户登录认证中';
?>
<?php
$this->beginBlock('JS_INIT')
?>
    function openWin(name) {
    api.openWin({
    name : name,
    url : 'widget://html/' + name + '_win.html',
    reload : true,
    pageParam: {
    redirect: "<?=$url?>"
    }
    });
    }
    apiready = function() {
    openWin('user/login');
    }
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_INIT'],\yii\web\View::POS_END);
?>