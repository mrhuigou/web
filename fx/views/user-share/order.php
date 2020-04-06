<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;
/* @var $this yii\web\View */
$this->title = $title;
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
    <section class="veiwport  pb50">

		<div class="w">
			<?= ListView::widget([
				'dataProvider' => $dataProvider,
				'layout' => '{items}{pager}',
				'itemOptions' => ['class' => 'item m5'],
				'emptyText'=>'<figure class="info-tips bg-wh gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有任何信息</figcaption></figure>',
				'emptyTextOptions' => ['class' => 'empty tc p10 '],
				'itemView' => 'order-list-item',
                'viewParams' => [//传参数给每一个item
                    'type' => $type
                ],
				'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
					'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
					'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
					'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
				],
			]); ?>
		</div>
	</section>
<?//= fx\widgets\MainMenu::widget(); ?>