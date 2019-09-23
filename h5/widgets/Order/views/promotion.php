<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/5/25
 * Time: 19:48
 */?>
<?php if($model){ ?>
    <div class="pt5">
        <ul class="clearfix">
            <?php foreach($model as $data){ ?>
                <li class="fl">
                    <i class="red">[赠品]</i> <?=$data->name?> [<?=$data->product->getSku()?>]  X <?=$data->quantity?>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>