<?php
use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\extensions\widgets\fileapi\Widget as FileAPI;
$this->title = '上传头像';
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
			<div class="user_center shadowBox whitebg clearfix">
				<ul class="orderStatus clearfix pl15 bdb pt15 pb10 mb20 f18">
					<li>
						<a href="<?=\yii\helpers\Url::to(['/profile'],true) ?>" class="f20 pl10 pr30">个人信息</a>
						<div class="" style="top:14px;"><b></b></div>
					</li>
					<li>
						<a href="<?=\yii\helpers\Url::to(['/profile/avatar'],true) ?>" class="f20 pl10 pr30">我的头像</a>
						<div class="st-arrow" style="top:14px;"><b></b></div>
					</li>
				</ul>
				<div class="clearfix w">
					<div class="bc tc">
					<?php $form = ActiveForm::begin(['id' => 'form-avatar',]);?>
						<?php
							echo $form->field($model, "photo")->widget(
							    FileAPI::className(),
							    [
							        'settings' => [
							            'url' => ['/profile/fileapi-upload'],
							        ],
							        'crop'=>true,
							        'template' => 'avatar',
							    ]
							)->label(false);
						 ?>
						<?php ActiveForm::end(); ?>
					</div>

				</div>
			</div>
		</div>
	</div>
    </div>
</div>