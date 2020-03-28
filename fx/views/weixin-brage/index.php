<?php
/* @var $this yii\web\View */
use \yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='识别二维码关注公共号';
?>
<section class="veiwport">
   <div class="p20 tc ">
	   <p class="green lh200 fb">长按识别二维码关注后，点击购买</p>
	   <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?=$model->ticket?>" class="w">
   </div>
</section>
