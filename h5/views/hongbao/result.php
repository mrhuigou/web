<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/11
 * Time: 15:48
 */
$this->title = "红包详情";
?>
<?= h5\widgets\Header::widget(['title' => $this->title]) ?>
	<div class="content">
		<div class=" pb50">
			<div class="redbag-top"></div>
			<div class="tc redbag-avatar">
				<?php if ($cur && ($cur->customer_id !== $model->customer_id)) { ?>
					<a href="javascript:;">
						<img src="<?= \common\component\image\Image::resize($cur->customer->photo, 100, 100) ?>"
						     width="80" height="80" class="img-circle bd">
					</a>
					<p class="pt5 pb10">
						<span class="f16 fb"><?= $cur->customer->nickname ?></span>
					</p>
					<?php if ($cur->amount > 0) { ?><p class="f12 pb10"><span
							class="fb f20 lh200"><?= $cur->amount ?></span>元</p>
						<p class="blue pb10 f12">已存入账户余额，下次购物使用</p>
					<?php } ?>
				<?php } else { ?>
					<a href="javascript:;">
						<img src="<?= \common\component\image\Image::resize($model->customer->photo, 100, 100) ?>"
						     width="80" height="80" class="img-circle bd">
					</a>
					<p class="pt5 pb10">
						<span class="f16 fb"><?= $model->customer->nickname ?></span>
					</p>
					<?php if($sub_total>0){?><p class="f12 pb10"><span class="fb f20 lh200"><?= $sub_total ?></span>元</p>
					<p class="blue pb10 f12">已存入账户余额，下次购物使用！</p>
					<?php }?>
				<?php } ?>
			</div>
			<p class="redbag-des gray9">
				共计<?= count($model->history) ?>位好友帮你拆红包。
			</p>
			<?php if ($history) { ?>
				<div class="pl5 pr5 whitebg">
					<?php foreach ($history as $key => $value) { ?>
						<div class="flex-col flex-middle redbag-list">
							<div class="flex-item-3 tc f16">
								<a href="javascript:;">
									<img
										src="<?= \common\component\image\Image::resize($value->customer->photo, 100, 100) ?>"
										width="50" height="50" class="img-circle">
								</a>
							</div>
							<div class="flex-item-5">
								<h3 class="f16 mb5"><?= $value->customer->nickname ? $value->customer->nickname : '匿名' ?></h3>

								<p class="gray9"><?= date('m-d H:i:s', $value->create_at) ?></p>
							</div>
							<div class="flex-item-4 tr pr10">
								<?php if ($value->amount > 0) { ?>
									<span class="fb"><?= $value->amount ?>元</span>
									<?php if ($key == $pos) { ?>
										<p class="org">
											<i class="iconfont vm">&#xe692;</i>
											<span class="vm">手气最佳</span>
										</p>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } else { ?>
				<p class="tc p5">当前还没有人拆呢！快去分享吧！！！</p>
			<?php } ?>
		</div>
	</div>
<?= h5\widgets\MainMenu::widget(); ?>