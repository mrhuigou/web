<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/10/27
 * Time: 16:26
 */
?>

<div class="detailCon2">
    <?php if($model){ ?>
    <div class="graybg p10">
        <p class="f14 pb5 org" >请确认收货地址</p>
        <div style="height: 150px;overflow-y:auto; ">
            <?php foreach($model as $value){ ?>
            <label class="mt10 db gray6">
                <?php if($value->default){ ?>
                <input type="radio" name="address_id" class="vm" value="<?=$value->address_id?>" checked> <span class="vm"><?=$value->citys?$value->citys->name:"青岛市"?>-<?=$value->district?$value->district->name:"市辖区"?>-<?=$value->address_1?> <?=$value->firstname?> <?=$value->telephone?> </span>
                <?php }else{ ?>
                <input type="radio" name="address_id" class="vm" value="<?=$value->address_id?>"> <span class="vm"><?=$value->citys?$value->citys->name:"青岛市"?>-<?=$value->district?$value->district->name:"市辖区"?>-<?=$value->address_1?> <?=$value->firstname?> <?=$value->telephone?> </span>
                <?php } ?>
            </label>
            <?php } ?>
        </div>
    </div>
        <div class="mt15 pb15 bd_dashB">
            <a class="btn btn_middle grayBtn mr5" href="<?=\yii\helpers\Url::to(['/address/create'])?>">使用新地址</a>
            <a class="btn btn_middle greenbtn SubmitTryBtn" href="javascript:;">申请报名</a>
        </div>
    <?php }else{ ?>
    <div class="graybg p10">
        <p class="f14 pb5 org" >您还没有添加过任何地址，请创建地址后操作！</p>
    </div>
        <div class="mt15 pb15 bd_dashB">
            <a class="btn btn_middle grayBtn mr5" href="<?=\yii\helpers\Url::to(['/address/create'])?>">创建新地址</a>
        </div>
    <?php } ?>
</div>
