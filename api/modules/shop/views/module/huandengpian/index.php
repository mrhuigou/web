<div class="skin-box tb-module tshop-pbsm tshop-pbsm-shop-main-slide tshop-pbsm-shop-main-slide-1414736829955">
	<style>.tshop-pbsm-shop-main-slide-1414736829955 .slide-box,.tshop-pbsm-shop-main-slide-1414736829955 .slide-box .slide-content li{height: 250px;}</style>
	<?php if(isset($data['display']['display']) && $data['display']['display']) { ?>
	<div class="skin-box-hd ">
		<i class="hd-icon"></i>
		<h3>
			<span>图片轮播</span>
		</h3>
		<div class="skin-box-act disappear">
			<a href="#" class="more">更多</a>
		</div>
	</div>
	<?php } ?>
	<?php if(isset($data['content'][0]['imagezone']) && $data['content'][0]['imagezone']) { ?>
	<div class="skin-box-bd">
		<div class="J_TWidget slide-box">
			<ul class="slide-content">
				<?php foreach($data['content'][0]['imagezone'] as $value){ ?>
				<li  class="ks-switchable-panel-internal458">
					<a target="_blank" title="<?php echo $value['title'];?>" href="<?php echo $value['image'];?>" ><img src="<?php echo $value['image'];?>"></a>
                    <div class="title"><?php echo $value['title'];?></div>
					<div class="sub-title"></div>
					<div class="other"></div>
                </li>
				<?php } ?>
			</ul>
			<div class="slide-triggers-bg"></div>
			<div class="hd">
				<ol class="slide-triggers">
					<?php foreach($data['content'][0]['imagezone'] as $key=>$value){ ?>
					   <li class="ks-switchable-trigger-internal457"><span><?php echo $key+1;?></span><s></s></li>
					<?php } ?>
				</ol>
			</div>
			<div class="prev-btn prev" style="top: 115px;">
				<div class="prev-next-bg"></div>
				<div class="text"></div>
			</div>
			<div class="next-btn next" style="top: 115px;">
				<div class="prev-next-bg"></div>
				<div class="text"></div>
			</div>
		</div>
	</div>
	<?php } ?>

	</div>