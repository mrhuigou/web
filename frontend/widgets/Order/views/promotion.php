<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/5/25
 * Time: 19:48
 */?>
<?php if($model){ ?>
    <div class="pt5 bd_dashT">
        <h2 class="red">赠品</h2>
        <ul class="clearfix">
            <?php foreach($model as $data){ ?>
                <li class="fl">
                    <img src="<?=\common\component\image\Image::resize($data->product->image,50,50)?>" class="vm  bd" style="width: 25px; height: 25px;" title="<?=$data->name?> [<?=$data->product->getSku()?>]" />
                    <?=$data->name?> [<?=$data->product->getSku()?>]
                    X <?=$data->quantity?>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>