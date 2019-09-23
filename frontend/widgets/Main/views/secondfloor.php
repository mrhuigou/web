<!--楼层1-->
<?php if(isset($data['img_promotion_1_data']) && !empty($data['img_promotion_1_data'])){?>
    <?php foreach($data['img_promotion_1_data'] as $key => $banner){?>
        <?php if($key < 1){?>
            <a target="_blank" class="db mt10 scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?php echo \common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9);?>" alt="<?php echo $banner->title;?>" width="1200" height="80" class="db lazy" /></a>
        <?php }?>
    <?php }?>
<?php }?>
<div class="floor-container floor1">
    <div class="clearfix pt10 pb10" style="border-bottom:2px solid #6cbb18;">
        <div class="bg"></div>
        <div class="fl lh250">
            <i class="iconfont green f30 mr5 vm">&#xe62d;</i><span class="f18 fb vm">生鲜</span>
        </div>
        <div class="floor-nav clearfix mt10">

            <?php if(isset($data['txt_right_top_data']) && !empty($data['txt_right_top_data'])){?>
                    <?php foreach($data['txt_right_top_data'] as $key => $text){?>
                        <?php if($key < 12){?>
                            <a href="<?php echo $text->link_url?>" target="_blank"><?php echo $text->title?></a>
                        <?php }?>
                    <?php }?>
            <?php }?>
        </div>
    </div>

    <div class="clearfix whitebg">
        <div class="w240 fl">
            <?php if(isset($data['img_left_top1_data']) && !empty($data['img_left_top1_data'])){?>

                    <?php foreach($data['img_left_top1_data'] as $key => $banner){?>
                        <?php if($key < 1){?>
                            <a target="_blank" class="scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9)?>" alt="<?php echo $banner->title;?>" width="240" height="240" class="db lazy" /></a>
                        <?php }?>
                    <?php }?>

            <?php }?>

            <?php if(isset($data['txt_left_bottom1_data']) && !empty($data['txt_left_bottom1_data'])){?>
            <div class="clearfix f14 pr15" style="height: 125px;overflow: hidden;">
                <?php foreach($data['txt_left_bottom1_data'] as $key => $text){?>
                    <?php if($key < 12){?>
                        <a href="<?php echo $text->link_url?>" target="_blank" class="fl gray6 pt10 pl20 nowrap"><?php echo $text->title?></a>
                    <?php }?>
                <?php }?>
            </div>
            <?php }?>
        </div>
        <div class="w384 fl">
            <div class="slideBox" style="width: 384px;">
                <?php if(!empty($data['img_middle_left_topn_data'])){?>
                    <div class="hd">
                        <ul>
                            <?php $count = count($data['img_middle_left_topn_data']);?>
                            <?php for($i = 0; $i< $count ;$i++ ){?>
                                <li><?php echo $i+1; ?></li>
                            <?php }?>
                        </ul>
                    </div>
                    <div class="bd">
                        <ul>
                            <?php foreach($data['img_middle_left_topn_data'] as $banner){?>
                                <li><a href="<?php echo $banner->link_url;?>" class="" target="_blank"><img class="db lazy" src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9)?>" alt="<?php echo $banner->title;?>"width="384" height="384" /></a></li>
                            <?php }?>
                        </ul>
                    </div>
                <?php }?>

            </div>
        </div>
        <div class="w576 fl clearfix">
            <div class="Items smp">
                <div class="item-wrap item-3">
                    <?php if(isset($data['img_rigth_top2_data']) && !empty($data['img_rigth_top2_data'])){?>
                    <?php foreach($data['img_rigth_top2_data'] as $key => $banner){?>
                            <?php if($banner->advertise_media_type=='PACK' && $banner->product){?>
                    <div class="item">
                        <div class="item-padding">
                            <div class="item-inner">
                                <div class="item-photo">
                                    <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$banner->product->store_code,'product_code'=>$banner->product->product_code])?>" target="_blank"> <img data-url="<?=\common\component\image\Image::resize($banner->product->image,200,200)?>" src="/assets/images/placeholder.png" class="db w lazy" alt="<?=$banner->product->description->name?>" /> </a>
                                </div>
                                <div class="item-detail">
                                    <a href="<?=\yii\helpers\Url::to(['/product/index','shop_code'=>$banner->product->store_code,'product_code'=>$banner->product->product_code])?>" target="_blank" class="item-name" title="<?=$banner->product->description->name?>">
                                       <?=$banner->product->description->name?>
                                    </a>
                                    <div class="item-des">
                                        <?=$banner->product->description->meta_description?>
                                    </div>
                                    <div class="item-price">
													<span class="item-price-2">
														 <?=$banner->product->getPrice()?>
													</span>
													<span class="item-price-1">
														 <?=$banner->product->price?>
													</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                            <?php }else{?>
                                <div class="item item1">
                                    <div class="item-padding">
                                        <div class="item-inner">
                                            <div class="item-photo">
                                                <a href="<?php echo $banner->link_url;?>"> <img class="db lazy" src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9)?>" alt="<?php echo $banner->title;?>"> </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                    <?php }?>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix">
        <?php if(isset($data['pack_bottom3_data']) && !empty($data['pack_bottom3_data'])){?>
            <?php foreach($data['pack_bottom3_data'] as $key => $banner){?>
                <?php if($key < 5){?>
                    <a target="_blank" class="w240 fl scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?php echo \common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9);?>" alt="<?php echo $banner->title;?>" width="240" height="340" class="db lazy" /></a>
                <?php }?>
            <?php }?>
        <?php }?>

    </div>
    <?php if(isset($data['brand_bottomn_data']) && !empty($data['brand_bottomn_data'])){?>
        <div class=" brands-box pr">
            <div class="hd">
                <span class="next iconfont fb gray9">&#xe608;</span>
                <span class="prev iconfont fb gray9">&#xe609;</span>
            </div>
            <div class="bd brands mt5" style="border: 0;">
                <ul class="clearfix">
                    <?php foreach($data['brand_bottomn_data'] as $key => $banner){?>
                        <li><a target="_blank" class="scaleimg" href="<?php echo $banner->link_url;?>"><img  src="/assets/images/placeholder.png" data-url="<?php echo \common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height,9);?>" alt="<?php echo $banner->title;?>" width="120" height="65" class="db lazy" /></a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    <?php }?>

</div>