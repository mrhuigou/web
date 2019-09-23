<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '账户充值';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
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
                <div class="user_center clearfix whitebg ">
                    <style>
                        .help-block {
                            display: inline-block;
                        }
                    </style>
                    <div class="">
                        <div class="whitebg">
                            <div class="orderCata clearfix">
                                <a href="<?= Url::to(['/account-recharge'], true); ?>">在线充值</a>
                                <a href="<?= Url::to(['/account-recharge/card'], true); ?>" class="cur">充值卡充值</a>
                            </div>
                            <?php if (\Yii::$app->getSession()->getFlash('error')): ?>
                                <p class="help-block help-block-error"><?= \Yii::$app->getSession()->getFlash('error') ?></p>
                            <?php endif ?>
                            <div class="p30">
                                <?php $form = ActiveForm::begin(); ?>
                                <?= $form->field($model, 'card_no', [
                                    'template' => "<span class=\"f14 vm\">充值卡号：</span>{input}<span class=\"vm red\">{error}</span>",
                                    "inputOptions" => ["autocomplete" => "off", 'placeholder' => '充值卡号', 'class' => 'input minput w'],
                                ])->label(false) ?>
                                <?= $form->field($model, 'card_pin', [
                                    'template' => "<span class=\"f14 vm\">充值密码：</span>{input}<span class=\"vm red\">{error}</span>",
                                    "inputOptions" => ["maxlength" => "16", "autocomplete" => "off", 'placeholder' => '充值密码', 'class' => 'input minput w'],
                                ])->label(false) ?>
                               <div class="p10">
                                   <?= Html::submitButton('提交', ['class' => 'btn mbtn w  greenbtn', "id" => "subBtn", 'name' => 'submit-button']) ?>
                               </div>
                                <?php ActiveForm::end(); ?>
                                <p class="org p10 ">友情提示：每个券只能使用一次，充值后，券作废；暂不支持提现。</p>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
