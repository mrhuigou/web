<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/11/9
 * Time: 15:25
 */
?>

<div class="lottery-wrap">
	<div class="pw80 bc">
		<?php if(Yii::$app->user->getId()==$model->customer_id){?>
		<div class="whitebg f12 br5 pr" style="height:27rem ">
			<div class="pr pt30"  style="height:22rem ">
				<div class="hb hb3">
					<div class="c4">
						<img src="/assets/images/h5.png">
					</div>
					<div class="c2">
						<p class="f12">恭喜获得一个</p>
						<p class="f14 yellow fb">人气红包</p>
					</div>
				</div>
				<div class="tc  graybg pa-b p10 br5 m10">
					证明你的人气的时候到啦！！！
				</div>
			</div>
			<div class=" pa-b p10 ">
				<a class="btn w lbtn red  fb f14 share-guide " style="background: #ffdc00 none repeat scroll 0 0">喊人帮我拆红包</a>
			</div>
		</div>
		<?php }else{ ?>

		<?php }?>
	</div>
</div>
<?php if(Yii::$app->user->getId()==$model->customer_id){?>
<?php h5\widgets\Wx\Share::widget([
	'title'=>'帮我拆红包，我要买买买!',
	'desc'=>'水果、饮料、日常用品。在每日惠购1元就“购”了!',
	'link'=>Yii::$app->request->getAbsoluteUrl(),
	'imgUrl'=>Yii::$app->request->getHostInfo().'/assets/images/hongbao_share.jpg'
]);
?>
<?php }else{ ?>
<?php h5\widgets\Wx\Share::widget([
	'title'=>'点我马上开始1元购物!',
	'desc'=>'水果、饮料、日常用品。在每日惠购1元就“购”了!',
	'link'=>Yii::$app->request->getAbsoluteUrl(),
	'imgUrl'=>Yii::$app->request->getHostInfo().'/assets/images/fudai.png'
]);
?>
<?php } ?>
