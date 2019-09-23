<?php
use yii\widgets\Breadcrumbs;

$this->title = '地址管理';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class=" pb10" style="min-width:1100px;">
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
						<h2 class="p10 ">
							<a href="<?= \yii\helpers\Url::to(['/address/create'], true) ?>" id="addAddress"
							   class="btn btn_small orgBtn f12 lh180">新增地址</a>
						</h2>

						<div class="p10">
							<table cellpadding="0" cellspacing="0" class="shopcart_list bdb w">
								<thead>
								<tr>
									<th width="10%" class="f12">收货人</th>
									<th width="10%" class="f12">手机</th>
									<th width="45%" class="f12">收货地址</th>
									<th class="f12">操作</th>
								</tr>
								</thead>
								<?php if ($address) { ?>
									<?php foreach ($address as $result) { ?>
										<tr class="<?php echo \Yii::$app->user->identity->address_id == $result->address_id ? 'greenbg3' : ''; ?> nothingbg">
											<td><?php echo $result->firstname; ?></td>
											<td><?php echo $result->telephone; ?></td>
											<td>
												<p class="fb"><?=$result->zone->name?>-<?=$result->citys->name?>-<?=$result->district->name?></p>
												<p>
													<?php if($result->poiname && strpos($result->address_1, $result->poiname) !== false){ ?>
														<?=$result->address_1?>
													<?php }else{ ?>
														<?=$result->poiaddress?><?=$result->poiname?><?=$result->address_1?>
													<?php } ?>
											</p>
											</td>
											<td class="text-center">
												<?php if (\Yii::$app->user->identity->address_id == $result->address_id) { ?>
													<a class="gray6 defaultaddress default" href="javascript:void(0);">默认地址</a>
												<?php } else { ?>
													<a class="org defaultaddress myPointer"
													   href="<?= \yii\helpers\Url::to(['/address/setdefault', 'id' => $result->address_id], true) ?>">设为默认</a>
												<?php } ?>
												|

												<a href="<?= \yii\helpers\Url::to(['/address/update', 'id' => $result->address_id], true) ?>" class="blue " style="cursor:pointer">修改</a>
											      |
												<?= \yii\helpers\Html::a('删除', ['delete', 'id' => $result->address_id, 'redirect' => Yii::$app->request->get('redirect')], [
													'class' => 'blue',
													'data' => [
														'confirm' => '您确认要删除当前地址吗？',
														'method' => 'post',
													],
												]) ?>
											</td>
										</tr>
									<?php } ?>
								<?php } else { ?>
									<p class="tc bd">提示：您还没有任何地址信息!</p>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
