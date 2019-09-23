<!--特色同城-->
<h3 class="f20 pt20 fb pb20 none">特色同城</h3>
<div class="onecity">
    <h3>特色同城</h3>
</div>
<div class="clearfix">
    <?php if(isset($data['img_left1_data']) && !empty($data['img_left1_data'])){?>
        <div class="w240 fl">
            <?php foreach($data['img_left1_data'] as $key => $banner){?>
                <?php if($key < 2){?>
                    <a target="_blank" class="scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9)?>" alt="<?php echo $banner->title;?>" width="240" height="240" class="db lazy" /></a>
                <?php }?>
            <?php }?>
        </div>
    <?php }?>

    <div class="w384 fl">
        <?php if(isset($data['img_middle_left_top1_data']) && !empty($data['img_middle_left_top1_data'])){?>
            <?php foreach($data['img_middle_left_top1_data'] as $key => $banner){?>
                <?php if($key < 1){?>
                    <a target="_blank" class="scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9)?>" alt="<?php echo $banner->title;?>" width="384" height="330" class="db lazy" /></a>
                <?php }?>
            <?php }?>
        <?php }?>
        <?php if(isset($data['img_middle_left_bottom1_data']) && !empty($data['img_middle_left_bottom1_data'])){?>
            <?php foreach($data['img_middle_left_bottom1_data'] as $key => $banner){?>
                <?php if($key < 1){?>
                    <a target="_blank" class="scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9)?>" alt="<?php echo $banner->title;?>" width="384" height="150" class="db lazy" /></a>
                <?php }?>
            <?php }?>
        <?php }?>
    </div>
    <div class="w576 fl">
        <div class="clearfix">
            <div class="w384 fl">

                <?php if(isset($data['img_middle_right_top2_data']) && !empty($data['img_middle_right_top2_data'])){?>
                    <?php foreach($data['img_middle_right_top2_data'] as $key => $banner){?>
                        <?php if($key < 2){?>
                            <a target="_blank" class="scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9)?>" alt="<?php echo $banner->title;?>" width="384" height="165" class="db lazy" /></a>
                        <?php }?>
                    <?php }?>
                <?php }?>

            </div>
            <div class="w192 fl">

                <?php if(isset($data['img_rigth_top2_data']) && !empty($data['img_rigth_top2_data'])){?>
                    <?php foreach($data['img_rigth_top2_data'] as $key => $banner){?>
                        <?php if($key < 2){?>
                            <a target="_blank" class="scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9)?>" alt="<?php echo $banner->title;?>" width="192" height="165" class="db lazy" /></a>
                        <?php }?>
                    <?php }?>
                <?php }?>
            </div>
        </div>
        <div class="clearfix">
            <?php if(isset($data['pack_right_bottom3_data']) && !empty($data['pack_right_bottom3_data'])){?>
                <?php foreach($data['pack_right_bottom3_data'] as $key => $banner){?>
                    <?php if($key < 3){?>
                        <a target="_blank" class="fl scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9)?>" alt="<?php echo $banner->title;?>" width="192" height="150" class="db lazy" /></a>
                    <?php }?>
                <?php }?>
            <?php }?>
        </div>
    </div>
</div>