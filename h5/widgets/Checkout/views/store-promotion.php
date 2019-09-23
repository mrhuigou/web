<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/19
 * Time: 15:39
 */
?>
<?php if($model){ ?>
    <div class="p10">
        <h2 class="red">店铺赠品</h2>
        <ul class="clearfix">
            <?php foreach($model as $data){ ?>
                <li class="fl">
                    <img src="<?=\common\component\image\Image::resize($data->image,50,50)?>" class="vm  bd" style="width: 25px; height: 25px;" title="<?=$data->description->name?> [<?=$data->getSku()?>]" />
                    <?=$data->description->name?> [<?=$data->getSku()?>]
                    X <?=$data->quantity?>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
<?php if($coupon_gifts){ ?>
    <div class="p10">
        <h2 class="red">优惠券赠品</h2>
        <ul class="clearfix">
            <?php foreach($coupon_gifts as $data){ ?>
                <li class="fl">
                    <img src="<?=\common\component\image\Image::resize($data->product->image,50,50)?>" class="vm  bd" style="width: 25px; height: 25px;" title="<?=$data->product->description->name?> [<?=$data->product->getSku()?>]" />
                    <?=$data->product->description->name?> [<?=$data->product->getSku()?>]
                    X <?=$data->qty?>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>