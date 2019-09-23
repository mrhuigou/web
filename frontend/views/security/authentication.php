<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\Breadcrumbs;
$this->title = '实名认证';
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
                        <h2 class="titStyle3 f14 green graybg2">实名认证</h2>

                        <p class="whitebg p15 bdb  ">
                            <span class="icon_light org">友情提示：实名认证用于提升账户安全性和信任级别，可享受更多家润网服务，如货到卡付等。认证信息设置成功后不能修改，请务必填写真实信息。</span>
                        </p>
                        <?php if (Yii::$app->getSession()->getFlash('error')) { ?>
                            <div class="login-msg bc w400">
                                <p class="error m5">
                                    <?= Yii::$app->getSession()->getFlash('error'); ?>
                                </p>
                            </div>
                        <?php } ?>
                        <div class="whitebg  mb20 w400 bc" id="form">
                            <?php $form = ActiveForm::begin(['fieldConfig' => ['options' => ['class' => 'p5 clearfix'], 'labelOptions' => ['class' => 'db fb w tl '], 'errorOptions' => ['tag' => 'p'], 'inputOptions' => ['class' => 'input linput w']]]); ?>
                            <?= $form->field($model, 'name') ?>
                            <?= $form->field($model, 'cert') ?>
                            <?= $form->field($model, 'card') ?>
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
</div>