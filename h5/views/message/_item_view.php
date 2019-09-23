
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
    <a  href="javascript:void(0)" onclick="setRead('<?php echo $link;?>',<?php echo $model->message_id?>);">
        <li class="p5 whitebg">
            <p class="fb">
                <?=$title?>
            </p>
                <p><?=$model->date_added?></p>
            <p>
                <?php if($intro){?>
                    <p> <a href="<?php echo $link;?>" class="f14 fb"><?php echo $intro?></a></p>
                <?php }?>
            </p>
        </li>
<!--        <div class="p10 graybg">-->
<!--             查看详情-->
<!--        </div>-->
    </a>
    <?php }?>