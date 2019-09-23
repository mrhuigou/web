<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/5/9
 * Time: 14:21
 */?>

<?php if($datas){ ?>
    <div class="clearfix">
        <div class="orgbg p2 fl  mr5 mt5 white tc f14 " style="width: 23px;">赠</div>
        <?php foreach($datas as $data){ ?>
            <div class="fl m5  tc vm blue" >
                <img src="<?=\common\component\image\Image::resize($data->image,50,50)?>" class="vm  bd" width="25" height="25" title="<?=$data->description->name?> [<?=$data->getSku()?>]" />
                X <?=$data->quantity?>
            </div>
        <?php } ?>
    </div>
<?php } ?>