<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/3
 * Time: 15:21
 */
?>
<?php if($model){?>
<?php foreach ($model as $detail){?>
<a href="<?=\yii\helpers\Url::to($detail->link_url)?>"><img src="<?=\common\component\image\Image::resize($detail->source_url)?>" class="w"></a>
<?php }?>
<?php }?>
