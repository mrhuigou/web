<?php
use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '个人资料';
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

                <div class="user_center shadowBox whitebg clearfix" id="form">
                    <ul class="orderStatus clearfix pl15 bdb pt15 pb10 mb20 f18">
                        <li>
                            <a href="<?= \yii\helpers\Url::to(['/profile'], true) ?>" class="f20 pl10 pr30">个人信息</a>

                            <div class="st-arrow" style="top:14px;"><b></b></div>
                        </li>
                        <li>
                            <a href="<?= \yii\helpers\Url::to(['/profile/avatar'], true) ?>"
                               class="f20 pl10 pr30">我的头像</a>

                            <div class="" style="top:14px;"><b></b></div>
                        </li>
                    </ul>

                    <div class="p15">
                        <?php if (\Yii::$app->getSession()->getFlash('success')) { ?>
                            <div class="msg-success"><?= \Yii::$app->getSession()->getFlash('success'); ?></div>
                        <?php } ?>
                        <?php $form = ActiveForm::begin(['id' => 'form-profile', 'fieldConfig' => [
                            'template' => "{input}{error}",
                            'inputOptions' => ["class" => 'input'],
                            'errorOptions' => ['class' => 'red mt5 mb5 db']
                        ],]); ?>
                        <div class="p5 pl15 fb">基本信息</div>
                        <div class="line_dash mb5"></div>
                        <div class="clearfix">
                            <style type="text/css">
                                #profileform-signature {
                                    width: 300px;
                                }
                            </style>
                            <table cellpadding="0" cellspacing="0" class="tableP58 w700 fl">
                                <tr>
                                    <td width="25%" align="right"><span class="org">*</span>昵称：</td>
                                    <td>
                                        <?= $form->field($model, 'nickname') ?>
                                        <span id=""></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">会员等级：</td>
                                    <td>
                                        <span class="green pl30 icon_rate1">普通用户</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right"><span class="org">*</span>手机：</td>
                                    <td>
                                        <p class="show_box">
                                            <span
                                                class="fb pr20 show_text"><?= $model['telephone'] ? substr_replace($model['telephone'], '****', 3, 4) : '未验证'; ?></span>
                                            <!--
                                            <span class="green icon_pencial pr10 cp modify_btn">修改</span>
                                            <span class="gray0">已验证</span>-->
                                        </p>
                                        <span id="telephone_li" class="red"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right"><span class="org">*</span>邮箱：</td>
                                    <td>
                                        <p class="show_box">
                                            <span
                                                class="fb pr20 show_text"><?= $model->email ? $model->email : '未验证' ?></span>
                                            <!--
                                            <span class="green icon_pencial pr10 cp modify_btn">修改</span>
                                            <span class="org">未验证</span>-->
                                        </p>
                                        <span id="email_li"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right"><span class="org">*</span>性别：</td>
                                    <td>
                                        <?= $form->field($model, 'gender')->inline(true)->label(false)->radioList(['男' => '男', '女' => '女']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">出生日期：</td>
                                    <td>
                                        <?php foreach (range(2015, 1920) as $value) {
                                            $years[$value] = $value;
                                        } ?>
                                        <?= Html::dropDownList('ProfileForm[birth_year]', $model->birth_year, $years); ?>

                                        <?php foreach (range(1, 12) as $value) {
                                            $monthes[sprintf("%02d", $value)] = sprintf("%02d", $value);
                                        } ?>
                                        <?= Html::dropDownList('ProfileForm[birth_month]', $model->birth_month, $monthes); ?>

                                        <?php foreach (range(1, 31) as $value) {
                                            $days[sprintf("%02d", $value)] = sprintf("%02d", $value);
                                        } ?>
                                        <?= Html::dropDownList('ProfileForm[birth_day]', $model->birth_day, $days); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">所在地区：</td>
                                    <td id="city">
                                        <select class="prov" id="zone_id">
                                            <option>山东省</option>
                                        </select>
                                        <select class="city" id="city_id">
                                            <option>青岛市</option>
                                        </select>
                                        <?= Html::dropDownList('ProfileForm[district_id]', $model->district_id, $district); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="25%" align="right">个性签名：</td>
                                    <td>
                                        <?= $form->field($model, 'signature') ?>
                                    </td>
                                </tr>

                            </table>

                            <div class="avaUpload fr mr50 mt30">
                                <img
                                    src="<?= \common\component\image\Image::resize(Yii::$app->user->identity->photo, 100, 100); ?>"
                                    alt="tu" width="100" height="100"/>

                                <div class="pop none">
                                    <i></i>
                                    <a href="<?php //echo $avatarUrl;?>"
                                       class="btn btn_small orgBtn"><?php //echo $edit_photo?'修改':'上传';?>头像</a>
                                </div>
                            </div>
                        </div>

                        <div class="p5 pl15 mt20 fb">选填信息</div>
                        <div class="line_dash mb5"></div>
                        <table cellpadding="0" cellspacing="0" class="tableP58 w">
                            <tr>
                                <td width="7%" align="right">学历：</td>
                                <td>
                                    <?= Html::dropDownList('ProfileForm[education]', $model->education, yii\helpers\ArrayHelper::map($education, 'name', 'name')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">职业：</td>
                                <td>
                                    <?= Html::dropDownList('ProfileForm[occupation]', $model->occupation, yii\helpers\ArrayHelper::map($occupation, 'name', 'name')); ?>
                                </td>
                            </tr>
                        </table>

                        <div class="line_dash mt15 mb15"></div>
                        <p class="clearfix tc pl50 pb50">
                            <?= Html::submitButton('保存', ['class' => 'btn btn_middle greenBtn ',]) ?>
                        </p>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
