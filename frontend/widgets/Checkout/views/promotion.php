<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/19
 * Time: 15:39
 */
?>
<?php if($datas){ ?>
        <div class="w">
            <?php foreach($datas as $data){ ?>
            <div class="m5  tc vm blue" >
               <i class="red">赠品</i>
                <?=$data->description->name?> [<?=$data->getSku()?>]
                 X <?=$data->quantity?>
            </div>
            <?php } ?>
        </div>
<?php } ?>

