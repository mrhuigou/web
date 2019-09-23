<div class="banner-slideBox">
    <?php if(isset($data['silde_position_data']) && !empty($data['silde_position_data'])){?>
        <div class="slideBox">
            <div class="hd">
                <ul>
                    <?php $count = count($data['silde_position_data']);?>
                    <?php for($i = 0; $i< $count ;$i++ ){?>
                        <li><?php echo $i+1; ?></li>
                    <?php }?>
                </ul>
            </div>
            <div class="bd">
                <ul>
                    <?php foreach($data['silde_position_data'] as $banner){?>
                        <li><a href="<?php echo $banner['link'];?>" target="_blank"><img class="db lazy" src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="1100" height="260" /></a></li>
                    <?php }?>
                </ul>
            </div>
        </div>
    <?php }?>

</div>