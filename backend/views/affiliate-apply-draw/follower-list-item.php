<?php
use \common\component\image\Image;
use \common\component\Helper\Datetime;
?>
<tr>
    <td><img src="<?=Image::resize($model->follower->photo,100,100)?>" width="40" height="40" alt="ava" class="pop-show"></td>
    <td><?=$model->follower->nickname?$model->follower->nickname:"陌生人"?></td>
    <td><?=Datetime::getTimeAgo(date('Y-m-d H:i:s',$model->creat_at))?></td>
</tr>