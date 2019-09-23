<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '优惠券激活';
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
                    <div class="">
                        <div class="whitebg">
                            <div class="orderCata clearfix">
                                <a href="<?= \yii\helpers\Url::to(['/user-coupon/index'], true); ?>" >我的优惠券</a>
                                <a href="<?= \yii\helpers\Url::to(['/user-coupon/card'], true); ?>" class="cur">优惠券充值</a>
                            </div>

                            <div class="p30">
                                <?php $form =ActiveForm::begin(['id' => 'form-address','fieldConfig' => [
                                    'template' => "<div class=\"mt10 mb10 clearfix\">{input}</div>{error}",
                                    'inputOptions' => ["class"=>'input linput tl w'],
                                    'errorOptions'=>['class'=>'red mt5 mb5 db']
                                ],  ]);?>
                                <?= $form->field($model, 'card_code',['inputOptions'=> ["placeholder"=>'优惠券卡号']]) ?>
                                <?= $form->field($model, 'card_pwd',['inputOptions'=> ["placeholder"=>'优惠券密码']]) ?>
                                <div class="fixed-bottom tc lh">
                                    <?= Html::submitButton('确认激活', ['class' => 'btn p10 greenbtn pr15 pl15 w', 'name' => 'save-button']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
