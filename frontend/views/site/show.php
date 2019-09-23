

<!--二楼-->
<div class="floor-container floor2 clearfix">
    <div class="floor-title clearfix">
        <a name="floor2" class="fl"><img src="/assets/images/2015/title-show.png" alt="慧秀"></a>
        <div class="floor-nav clearfix">
            <?php if(isset($data['text_top_position_data']) && !empty($data['text_top_position_data'])){?>
                <?php foreach($data['text_top_position_data'] as $key => $text){?>
                    <?php if($key < 9){?>
                        <a href="<?php echo $text['link']?>" target="_blank"><?php echo $text['title']?></a>
                    <?php }?>

                <?php }?>
            <?php }?>
        </div>
    </div>

    <!--新列表-->
    <div style="width:188px;height:302px;" class="fl">
        <div class="category-menu">
            <?php if(isset($data['category_array']) && !empty($data['category_array'])){?>
                <div class="category-nav">
                    <ul class="menu-nav-container">
                        <?php foreach($data['category_array'] as $key => $second){?>
                            <li>
                                <i class="fr iconfont"></i>
                                <?php $count1 = count($second);?>
                                <p>
                                    <?php foreach($second as $k => $second_info){?>

                                        <?php if(is_int($k)){?>
                                            <?php if( $k < ($count1-1) ){?>
                                                <a target="_blank" href="<?php echo $second_info['href']?>"><?php echo $second_info['name']?></a>/
                                            <?php }else{?>
                                                <a target="_blank" href="<?php echo $second_info['href']?>"><?php echo $second_info['name']?></a>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>

                                </p>

                            </li>
                        <?php }?>
                    </ul>
                </div>
            <?php }?>

            <div class="category-content">
                <?php if(isset($data['category_array']) && !empty($data['category_array'])){?>
                    <?php foreach($data['category_array'] as $key => $second){?>
                        <div class="menu-content-panel">
                            <?php foreach($second as $k => $second_info){?>
                                <h2><a href="<?php echo $second_info['href']?>" target="_blank"><?php echo $second_info['name']?></a></h2>
                                <?php if(isset($second_info['child_category_arrays']) && !empty($second_info['child_category_arrays'])){?>
                                    <p>
                                        <?php foreach($second_info['child_category_arrays'] as $third){?>

                                            <a target="_blank" href="<?php echo yii\helpers\Url::to(['/category/index','category_id' => $third->category_display_id])?>"><?php echo $third->description->name;?></a>
                                        <?php }?>

                                    </p>
                                <?php }?>
                            <?php }?>
                        </div>
                    <?php }?>
                <?php }?>
            </div>

        </div>
    </div>

    <div class="clearfix">
        <div class="slideBox fl ml5 mr5" style="width:345px;">
            <?php if(isset($data['silde_position_data']) && !empty($data['silde_position_data'])){?>
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
                            <li><a target="_blank" href="<?php echo $banner['link'];?>" target="_blank"><img class="db lazy"  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="345" height="302" /></a></li>
                        <?php }?>
                    </ul>
                </div>
            <?php }?>
        </div>
        <?php if(isset($data['focus_position_data']) && !empty($data['focus_position_data'])){?>

            <div class="fl img180 slideEffect clearfix" style="width:182px;">
                <?php foreach($data['focus_position_data'] as $key => $banner){?>
                    <?php if($key < 2){?>
                        <a target="_blank" href="<?php echo $banner['link'];?>"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="190" height="147" class="db lazy" /></a>
                    <?php }?>
                <?php }?>
            </div>

            <div class="fl slideEffect ml5 mr5" style="width:182px;">
                <?php if(isset($data['focus_position_data'][2]) && !empty($data['focus_position_data'][2])){?>
                    <a target="_blank" href="<?php echo $data['focus_position_data'][2]['link'];?>" class="bdShdow db mb5" style="height:301px;"><img  src="/assets/images/placeholder.png" data-url="<?php echo $data['focus_position_data'][2]['image'];?>" alt="<?php echo $data['focus_position_data'][2]['title'];?>" width="190" height="301" class="db lazy" /></a>
                <?php }?>
            </div>

            <div class="fl img180 slideEffect clearfix" style="width:182px;">
                <?php foreach($data['focus_position_data'] as $key => $banner){?>
                    <?php if($key >2 && $key <=4 ){?>
                        <a target="_blank" href="<?php echo $banner['link'];?>"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="190" height="147" class="db lazy" /></a>
                    <?php }?>
                <?php }?>

            </div>
        <?php }?>

    </div>

    <div class="clearfix">
        <?php if(isset($data['show_position_fix_top_data']) && !empty($data['show_position_fix_top_data'])){?>
            <div class="fl slideEffect" style="width:189px;">
                <?php if(isset($data['show_position_fix_top_data'][0]) && !empty($data['show_position_fix_top_data'][0])){?>
                    <a target="_blank" href="<?php echo $data['show_position_fix_top_data'][0]['link'];?>" class="fl" style="width:187px;height:198px;"><img  src="/assets/images/placeholder.png" data-url="<?php echo $data['show_position_fix_top_data'][0]['image'];?>" alt="<?php echo $data['show_position_fix_top_data'][0]['title'];?>" width="197" height="198" class="db lazy" /></a>
                <?php }?>
            </div>
            <div class="fl slideEffect ml5" style="width:345px;">
                <?php if(isset($data['show_position_fix_top_data'][1]) && !empty($data['show_position_fix_top_data'][1])){?>
                    <a target="_blank" href="<?php echo $data['show_position_fix_top_data'][1]['link'];?>" class="fl" style="width:343px;height:198px;"><img  src="/assets/images/placeholder.png" data-url="<?php echo $data['show_position_fix_top_data'][1]['image'];?>" alt="<?php echo $data['show_position_fix_top_data'][1]['title'];?>" width="353" height="198" class="db lazy" /></a>
                <?php }?>

            </div>
            <div class="fr img180 img1802 slideEffect clearfix" style="width:561px">
                <?php foreach($data['show_position_fix_top_data'] as $key => $banner){?>
                    <?php if($key > 1 && $key <5){?>
                        <a target="_blank" href="<?php echo $banner['link'];?>"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="190" height="198" class="db lazy" /></a>
                    <?php }?>
                <?php }?>
            </div>
        <?php }?>

    </div>

    <div class="clearfix">
        <?php if(isset($data['show_position_fix_bottom_data']) && !empty($data['show_position_fix_bottom_data'])){?>
            <div class="fl slideEffect" style="width:189px;">
                <?php if(isset($data['show_position_fix_bottom_data'][0]) && !empty($data['show_position_fix_bottom_data'][0])){?>
                    <a target="_blank" href="<?php echo $data['show_position_fix_bottom_data'][0]['link'];?>" class="fl" style="width:187px;height:198px;"><img  src="/assets/images/placeholder.png" data-url="<?php echo $data['show_position_fix_bottom_data'][0]['image'];?>" alt="<?php echo $data['show_position_fix_bottom_data'][0]['title'];?>" width="197" height="198" class="db lazy" /></a>
                <?php }?>
            </div>
            <div class="fl slideEffect ml5" style="width:345px;">
                <?php if(isset($data['show_position_fix_bottom_data'][1]) && !empty($data['show_position_fix_bottom_data'][1])){?>
                    <a target="_blank" href="<?php echo $data['show_position_fix_bottom_data'][1]['link'];?>" class="fl" style="width:343px;height:198px;"><img  src="/assets/images/placeholder.png" data-url="<?php echo $data['show_position_fix_bottom_data'][1]['image'];?>" alt="<?php echo $data['show_position_fix_bottom_data'][1]['title'];?>" width="353" height="198" class="db lazy" /></a>
                <?php }?>
            </div>
            <div class="fr img180 img1802 slideEffect clearfix" style="width:561px">
                <?php foreach($data['show_position_fix_bottom_data'] as $key => $banner){?>
                    <?php if($key > 1 && $key <5){?>
                        <a target="_blank" href="<?php echo $banner['link'];?>"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="190" height="198" class="db lazy" /></a>
                    <?php }?>
                <?php }?>
            </div>
        <?php }?>
    </div>

</div>