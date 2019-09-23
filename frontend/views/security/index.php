<?php 
use yii\widgets\Breadcrumbs;
$this->title = '账户安全';
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
	
		<?php if(\Yii::$app->getSession()->getFlash('success')){ ?>
				<div class="msg-success mb5"><?= \Yii::$app->getSession()->getFlash('success'); ?></div>
				<?php } ?>
		<div class="user_center clearfix whitebg ">
			<div class="">
				<table cellpadding="0" cellspacing="0" class="shopcart_list w bdb">		
					<?php if(isset($model['password']) && !empty($model['password'])){?>
						<tr>
							<td width="13%">
								<p class="org green">
									<span class="icon_key yes">&nbsp;</span><br />
									已设置
								</p>
							</td>
							<td width="13%" class="fb f18 tl">登录密码</td>
							<td width="50%" class="tl">安全性高的密码可以使账号更安全。建议您定期更换密码，且设置一个包含数字和字母，并长度超过6位以上的密码。</td>
							<td><a href="<?=\yii\helpers\Url::to(['/security/update-password'],true)?>" class="green">修改</a></td>
						</tr>
					<?php }?>

					<!-- 实名认证 -->
					<tr>
						<td width="13%">
						<?php 
						if((isset($model['idcard_validate']) && $model['idcard_validate'] == '2') ){
								$green_org = 'green';
								$style_yes = 'yes';
							}else{
								$green_org = 'org';
								$style_yes = '';
							}
							?>

							<p class="<?php echo $green_org;?>">
								<span class="icon_realName <?php echo $style_yes;?>">&nbsp;</span><br />
								<?php echo $model['idcard_validate'] == '2'?'已认证':'未认证'; ?>
							</p>
						</td>
						<td width="13%" class="fb f18 tl">实名认证</td>
						<td width="50%" class="tl">
							<?php if((isset($model['idcard_validate']) && $model['idcard_validate'] == '2') ){?>
							用于提升账号的安全性和信任级别,会影响到某些功能的使用如货到卡付等。认证后不能修改认证信息。
							<?php }else{?>
							安全性和信任级别低，不能使用如<span class="org">货到卡付</span>等功能。该信息认证后不能修改。
							<?php }?>
						</td>
						<td>
							<?php if($model['idcard_validate']=='3' || $model['idcard_validate'] == '0'){ ?>
								<a href="<?=\yii\helpers\Url::to(['/security/authentication'],true)?>" class="org">认证</a>
								<br><?php if(isset($customer_authen['description'])){echo '原因:'.$customer_authen['description'];}?>
							<?php }elseif($model['idcard_validate']=='2'){?>
							<span class="green">已认证</span>
							<?php }?>
						</td>
					</tr>

					<!-- 手机验证 -->
					<tr>
						<td width="13%">
						<?php 
							if(isset($model['telephone_validate']) && $model['telephone_validate'] =='1' ){
								$green_org = 'green';
								$style_yes = 'yes';
							}else{
								$green_org = 'org';
								$style_yes = '';
							}
							?>

							<p class="<?php echo $green_org;?>">
								<span class="icon_phone <?php echo $style_yes;?>">&nbsp;</span><br />
								<?=$model['telephone_validate'] == 1?'已验证':'未验证'; ?>
							</p>
						</td>
						<td width="13%" class="fb f18 tl">手机验证</td>
						<td width="50%" class="tl">
						<?php if(isset($model['telephone']) && !empty($model['telephone'])){?>
							您验证的手机： <span class="fb"><?=substr_replace($model['telephone'],'****',3,4);?></span>
						<?php }else{ ?>	
							您还未完善手机号码
						<?php }?>
						</td>
						<td><a href="<?=\yii\helpers\Url::to(['/security/security-set-telephone'],true)?>" class="<?php echo $green_org;?>">修改</a></td>
					</tr>

					<!-- 邮箱验证 -->
					<tr>
						<td width="13%">
						<?php 
							if(isset($model['email_validate']) && $model['email_validate'] =='1' ){
								$green_org = 'green';
								$style_yes = 'yes';
							}else{
								$green_org = 'org';
								$style_yes = '';
							}
							?>
							<p class="<?php echo $green_org;?>">
								<span class="icon_mail <?php echo $style_yes;?>">&nbsp;</span><br />
								<?=$model['email_validate'] == 1?'已验证':'未验证'; ?>
							</p>
						</td>
						<td width="13%" class="fb f18 tl">邮箱验证</td>
						<td width="50%" class="tl">
						用于登录或找回密码时我们会将新密码发到您的邮箱。
						<?php if(isset($model['email']) && $model['email'] ){?>
						您的邮箱：<span class="org"><?=substr_replace($model['email'],'****',3,4);?></span>
						<?php }?>
						</td>
						<td><a href="<?=\yii\helpers\Url::to(['/security/security-set-email'],true)?>" class="<?php echo $green_org;?>">修改</a></td>
					</tr>

					<!-- 支付密码 -->
					<tr>
						<td width="13%">
						<?php 
							if(isset($model['paymentpwd']) && !empty($model['paymentpwd'])){
								$green_org = 'green';
								$style_yes = 'yes';
							}else{
								$green_org = 'org';
								$style_yes = '';
							}
							?>
							<p class="<?php echo $green_org;?>">
								<span class="icon_lock <?php echo $style_yes;?>">&nbsp;</span><br />						
								<?=!empty($model['paymentpwd'])?'已设置':'未设置'; ?>
							</p>
						</td>
						<td width="13%" class="fb f18 tl">支付密码</td>
						<td width="50%" class="tl">设置后在使用账户余额、积分时需要输入。</td>
						<td><a href="<?=\yii\helpers\Url::to(['/security/security-update-paymentpwd'],true)?>" class="<?php echo $green_org;?>">修改</a></td>
					</tr>

				</table>
			</div>
		</div>
	</div>
</div>
	</div>
</div>
