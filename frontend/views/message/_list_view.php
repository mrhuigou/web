<!--时间-->
<div class="bd_dashB pb10 mt10">
    <?php if($model->messageContent){?>
        <?php
            $body = unserialize($model->messageContent->body);
            $title = $body['title'];
            $intro = $body['intro'];
            $image = $body['image'];
            $link =  'javascript:void(0)';
            if($model->messageContent->type == 'URL'){
                if(!empty($body['link'])){
                    $link = $body['link'];
                }
             }
        $body = unserialize($model->messageContent->body);
        $title = $body['title'];
        $intro = $body['intro'];
        $image = $body['image'];

        ?>
        <span class="iconfont <?php  if($model->is_read == 0){echo 'org';}?> mr5"></span>
            <?php if($model->is_read == 0){?>
                <a href="javascript:void(0)" onclick="setRead('<?php echo $link;?>',<?php echo $model->message_id?>);" class="f14 "> <?php echo $title;?></a>  <span style="float: right"><?php echo $model->date_added;?></span>
            <?php }else{?>
                <a href="<?php echo $link;?>" target="_blank" class="f14 "> <?php echo $title;?></a>  <span style="float: right"><?php echo $model->date_added;?></span>
            <?php }?>

        <?php if(!empty($image)){?>
            <?php if($model->is_read == 0){?>
                <p> <a  href="javascript:void(0)" onclick="setRead('<?php echo $link;?>',<?php echo $model->message_id?>);"  class="f14 "><img src="<?php echo \common\component\image\Image::resize($image,0,0); ?>" /> </a></p>
            <?php }else{?>
                <p> <a  href="<?php echo $link;?>" target="_blank" class="f14 "><img src="<?php echo \common\component\image\Image::resize($image,0,0); ?>" /> </a></p>
            <?php }?>

        <?php }?>
        <?php if($intro){?>
            <?php if($model->is_read == 0){?>
                <p> <a  href="javascript:void(0)" onclick="setRead('<?php echo $link;?>',<?php echo $model->message_id?>);"  class="f14 "><?php echo $intro?></a></p>
            <?php }else{?>
                <p> <a  href="<?php echo $link;?>" target="_blank"  class="f14 "><?php echo $intro?></a></p>
            <?php }?>

        <?php }?>
    <?php }else{?>
        <span class="iconfont <?php  if($model->is_read == 0){echo 'org';}?> mr5"></span>
        <a href="<?php echo \yii\helpers\Url::to(['message/info','message_id'=>$model->message_id])?>" class="f14 "> <?php echo $model->content?></a>  <span style="float: right"><?php echo $model->date_added;?></span>
    <?php }?>
</div>



