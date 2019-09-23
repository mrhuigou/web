<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/9/29
 * Time: 10:18
 */
?>
<?php if($model){ ?>
<h2 class="f14 graybg2 p10">本周热门活动</h2>
<ul class=" graybg">
    <?php foreach($model as $key=>$value){ ?>
    <li class="clearfix p10 bd_dashB">
        <i class="fl mt3 metrotag smt <?=$key<3?"orgbg":''?>"><?=$key+1?></i>
        <p class="ml20 wordbreak"><a href="<?=\yii\helpers\Url::to(['/club/default/info','id'=>$value->id])?>"><?=\yii\helpers\Html::encode($value->title)?></a></p>
        <div class="tr "><b class="red"><?=$value->tickets?></b> 报名</div>
    </li>
    <?php } ?>
</ul>
<?php } ?>