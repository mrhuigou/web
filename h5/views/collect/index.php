<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title ='我的收藏';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
	<ul class="filter  redFilter two bdb clearfix mt10">
		<li  class="<?=$type==2?'':'cur'?>"><a href="<?=Url::to(['/collect/index'],true)?>">商品</a></li>
		<li class="<?=$type==2?'cur':''?>"><a href="<?=Url::to(['/collect/index','type'=>'2'],true)?>">店铺</a></li>
	</ul>
	<?php if($type=='2'){ ?>
	<?=\yii\widgets\ListView::widget([
		'dataProvider' => $dataProvider,
		'itemOptions' => ['class' => 'item'],
		'emptyText'=>'<figure class="info-tips whitebg gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有信息</figcaption></figure>',
		'itemView' => '_item_store_view',
		'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
            'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
            'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
            'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
            'eventOnRendered' => 'function() { $("img.lazy").scrollLoading();}',
        ]
	])?>
	<?php }else{ ?>
		<?=\yii\widgets\ListView::widget([
			'dataProvider' => $dataProvider,
			'itemOptions' => ['class' => 'item'],
			'emptyText'=>'<figure class="info-tips whitebg gray9 p10"><i class="iconfont "></i><figcaption class="m10"> 当前没有信息</figcaption></figure>',
			'itemView' => '_item_view',
			'pager' => ['class' => \kop\y2sp\ScrollPager::className(),
                'enabledExtensions' => ['IASSpinnerExtension', 'IASNoneLeftExtension'],
                'triggerTemplate' => '<div class="w tc mt10 "><button class="ias-trigger appbtn pr10 pl10  ">加载更多</button></div>',
                'noneLeftTemplate' => '<div class="ias-noneleft tc p10">{text}</div>',
                'eventOnRendered' => 'function() { $("img.lazy").scrollLoading();}',
            ]
		])?>
	<?php } ?>
</section>