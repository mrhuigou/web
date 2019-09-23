<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
$this->title = '身份验证';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = ['label' => '账户安全', 'url' => ['/security']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="" style="min-width:1100px;">
    <div class="w1100 bc ">
        <!--面包屑导航-->
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'tag' => 'p',
            'options' => ['class' => 'gray6 pb5 pt5'],
            'itemTemplate' => '<a class="f14">{link}</a> > ',
            'activeItemTemplate' => '<a class="f14">{link}</a>',
        ]) ?>
        <div class="bc  clearfix simsun">
            <div class="fl w100 mr10 menu-tree">
                <?= frontend\widgets\UserSiderbar::widget() ?>
            </div>
            <div class="fl w990 ">
                <div class="user_center clearfix whitebg shadowBox">
                    <div class="">
                        <h2 class="titStyle3 f14 green graybg2">身份验证</h2>

                        <p class="whitebg p15 bdb mb10 ">
                            <span class="icon_light org">友情提示：为保障您的账户信息安全，在操作账户中的重要信息时需要进行身份验证，感谢您的理解和支持。若遇到问题，请尽快联系客服解决！</span>
                        </p>

                        <div class="whitebg  mb20 w400 bc" id="form">
                            <div class="p10 yellowbg">
                                <label class="fb">已验证手机：</label>
                                <span
                                    class="fb f18 mr20"><?php echo \common\component\Helper\Helper::str_mid_replace(Yii::$app->user->getIdentity()->telephone); ?></span><br><span
                                    class="green">您的手机号码，请先获取验证码，以验证身份</span>
                            </div>
                            <?php $form = ActiveForm::begin(['fieldConfig' => ['options' => ['class' => 'p5 clearfix'], 'labelOptions' => ['class' => 'db fb w tl '], 'inputOptions' => ['class' => 'input linput db fl placeholder w200'], 'errorOptions' => ['tag' => 'p']]]); ?>
                            <?= $form->field($model, 'verifyCode', ['template' => '{label}{input} <button type="button"  id="send-vcode" class=" ml10 btn w150 lbtn db " style="height: 41px;">获取语音验证码</button>{error}']) ?>

                            <div class="p5 tc">
                                <button type="submit" class="btn lbtn greenbtn w">确认提交</button>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->beginBlock('JS_END') ?>
    $("#send-vcode").on('click',function (e){
    time( $(this));
    $.post('/site/sendcode',{telephone:<?= Yii::$app->user->getIdentity()->telephone ?>});
    });
    <?php $this->endBlock() ?>
    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_END);
    ?>







