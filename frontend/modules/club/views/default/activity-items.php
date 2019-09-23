<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/10/28
 * Time: 9:54
 */
?>
<div class="tl p10 bc whitebg" >
    <form id="activityForm">
        <input type="hidden" name="activity_id" value="<?=$model->id?>">
    <?php if(!$model->items){ ?>
        <input type="hidden" name="activity_item_id" value="0" id="item_id">
        <table cellspacing="0" cellpadding="0" class="coupon cp w bluebg mb10 cur" id="0">
            <tr>
                <td width="35%" class="tit">
                   免费
                </td>
                <td class="whitebg">
                    <span class="tahoma blue">免费报名</span><br>
                    <p class="mt5 gray6">
                        <?php if($model->qty){?>
                        限 <?=$model->qty?> 人，剩余 <?=max(0,$model->qty-$model->tickets)?>
                    <?php }else{ ?>
                            人数不限
                        <?php } ?>
                    </p>
                    <i class="iconfont">&#xe627;</i>
                </td>
            </tr>
        </table>
    <?php }else{ ?>
        <?php foreach($model->items as $key=>$item){ ?>
            <?php if($key==0){?>
                <input type="hidden" name="activity_item_id" value="<?=$item->id?>">
            <?php } ?>
            <table cellspacing="0" cellpadding="0" class="coupon cp w bluebg mb10 <?=$key==0?'cur':''?>" id="<?=$item->id?>">
                <tr>
                    <td width="35%" class="tit">
                        <?=$item->fee?>
                    </td>
                    <td class="whitebg">
                        <span class="tahoma blue"><?=$item->name?></span><br>
                        <p class="mt5 gray6">
                            <?php if($item->quantity){?>
                                限 <?=$item->quantity?> 人
                            <?php }else{ ?>
                                人数不限
                            <?php } ?>
                        </p>
                        <i class="iconfont">&#xe627;</i>
                    </td>
                </tr>
            </table>
        <?php } ?>
    <?php } ?>
        <div class="p10">
            <p class="mt5 mb5">数量：</p>
            <p class="clearfix  pl5 pr20">
                <span class="num-lower iconfont">&#xe604;</span>
                <input type="text" class="num-text" value="1" name="quantity">
                <span class="num-add iconfont">&#xe605;</span>
            </p>
            <p class="mt5 mb5">姓名：</p>
            <input type="text" class="input minput w" name="username">
            <p class="mt5 mb5">手机号码：</p>
            <input type="text" class="input minput w" name="telephone">
            <?php if($model->option){ ?>
                <?php foreach($model->option as $option){ ?>
                    <?php if($option->type=='input'){?>
                    <p class="mt5 mb5"><?=$option->name?>：</p>
                    <input type="text" class="input minput w" name="option[<?=$option->id?>]">
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </div>
    <div class="tc p10 ">
        <button class="btn w btn_middle  grayBtn" type="submit">立即报名</button>
    </div>
    </form>
</div>

