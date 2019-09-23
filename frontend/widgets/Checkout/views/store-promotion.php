<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/19
 * Time: 15:39
 */
?>
<?php if($model){ ?>
    <div class="graybg p5 bdb  tl f14 ">店铺赠品</div>
    <div class="clearfix" style="height:60px;max-height: 100px;overflow: auto;">
        <?php foreach($model as $data){ ?>
            <div class="fl m5  tc vm blue" >
                <img src="<?=\common\component\image\Image::resize($data->image,50,50)?>" class="vm  bd" width="25" height="25" title="<?=$data->description->name?> [<?=$data->getSku()?>]" />
                <?=$data->description->name?> [<?=$data->getSku()?>]
                X <?=$data->quantity?>
            </div>
        <?php } ?>
    </div>
<?php } ?>
<?php if($coupon_gifts){ ?>
    <div class="graybg p5 bdb  tl f14 ">优惠券赠品</div>
    <div class="clearfix" style="height:60px;max-height: 100px;overflow: auto;">
        <?php foreach($coupon_gifts as $data){ ?>
            <div class="fl m5  tc vm blue" >
                <img src="<?=\common\component\image\Image::resize($data->product->image,50,50)?>" class="vm  bd" width="25" height="25" title="<?=$data->product->description->name?> [<?=$data->product->getSku()?>]" />
                <?=$data->product->description->name?> [<?=$data->product->getSku()?>]
                X <?=$data->qty?>
            </div>
        <?php } ?>
    </div>
<?php } ?>
