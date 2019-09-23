<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;
/* @var $this yii\web\View */
$this->title = '粉丝明细';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
	<section class="veiwport  pb50">
		<div class="flex-col tc lh37 aui-border whitebg f14  ">
			<a class="flex-item-4 " href="<?=\yii\helpers\Url::to(['/user-share/index'])?>">
				收益
			</a>
            <a class="flex-item-4  aui-border-l redbg white " href="<?=\yii\helpers\Url::to(['/user-share/follower'])?>">
                粉丝
            </a>
			<a class="flex-item-4 aui-border-l " href="<?=\yii\helpers\Url::to(['/user-share/commission'])?>">
				明细
			</a>
		</div>
        <div class="mt5  whitebg p10 fb ">概况</div>
        <div class="flex-col flex-center tc whitebg bdt bdb  p10">
            <div class="flex-item-6  flex-middle  p10 bdr bdb " >
                <p>当天引进</p>
                <p class="red"><?=$today_total?></p>
            </div>
            <div class="flex-item-6  flex-middle  bdb p10" >
                <p>当周引进</p>
                <p class="red"><?=$week_total?></p>
            </div>
            <div class="flex-item-6  flex-middle bdr  p10" >
                <p>当月引进</p>
                <p class="red"><?=$month_total?></p>
            </div>
            <div class="flex-item-6  flex-middle    p10" >
                <p>累计引进</p>
                <p class="red"><?=$history_total?></p>
            </div>
        </div>
		<div class="w">
			<?= ListView::widget([
				'dataProvider' => $dataProvider,
				'layout' => '{items}{pager}',
				'itemOptions' => ['class' => 'item m5'],
				'emptyText'=>'<figure class="info-tips bg-wh gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有任何信息</figcaption></figure>',
				'emptyTextOptions' => ['class' => 'empty tc p10 '],
				'itemView' => 'follower-list-item',
				'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
					'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
					'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
					'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
				]
			]); ?>
		</div>
	</section>
<?= h5\widgets\MainMenu::widget(); ?>