<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
$this->title = '修改支付密码';
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
                        <h2 class="titStyle3 f14 green graybg2">修改支付密码</h2>

                        <p class="whitebg p15 bdb mb10 ">
                            <span class="icon_light org">友情提示：为保障您的账户信息安全，在操作账户中的重要信息时需要进行身份验证，感谢您的理解和支持。若遇到问题，请尽快联系客服解决！</span>
                        </p>

                        <div class="mt15 bc w400 p20 ">
                            <?php $form = ActiveForm::begin(['fieldConfig' => ['options' => ['class' => 'p5 clearfix'], 'labelOptions' => ['class' => 'db fb w tl '], 'errorOptions' => ['tag' => 'p'], 'inputOptions' => ['class' => 'input linput w']]]); ?>
                            <?= $form->field($model, 'password', ["inputOptions" => ['maxlength' => 6, 'minlength' => 6]])->passwordInput() ?>
                            <?= $form->field($model, 'password_repeat', ["inputOptions" => ['maxlength' => 6, 'minlength' => 6]])->passwordInput() ?>
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