<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/5/5
 * Time: 16:59
 */?>
<label class="selfpick-item clearfix cur ">
    <div>
        <h2 class="fb"><?=$model->name?></h2>
        <p class="gray9 mt5 mb5">
            <span class="gray3">地址：</span>
            <?=$model->address?>
        </p>
        <p class="gray9">
            <span class="gray3">电话：</span>
            <?=$model->telephone?>
        </p>
    </div>
    <i class="iconfont">&#xe627;</i>
</label>
