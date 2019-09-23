<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/8
 * Time: 10:06
 */
$this->title =$model->title;
?>
<div class="w1100 bc">
	<!--面包屑导航-->
	<div class="bd mt10">
		<h3 class="p10 bd_dashB tc fb f14"><?=$this->title?></h3>
		<div class="m10 p20" style="min-height: 300px;">
			<?=\yii\helpers\Html::decode($model->description->description)?>
		</div>
	</div>
</div>
