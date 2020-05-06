<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/19
 * Time: 15:39
 */
?>
<?php if($datas){ ?>
    <div class="pt5">
            <ul class="clearfix">
            <?php foreach($datas as $data){ ?>
                <li class="fl">
                    <i class="red">[赠品]</i>
                    <?=$data->description->name?> [<?=$data->getSku()?>]
                    X <?=$data->quantity?>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

