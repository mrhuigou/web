<?php if($model){ ?>
    <h2 class="f14 graybg2 p10">热门话题</h2>
    <ul class=" graybg">
        <?php foreach($model as $key=>$value){ ?>
            <li class="clearfix p10 bd_dashB">
                <i class="fl mt3 metrotag smt <?=$key<3?"orgbg":''?>"><?=$key+1?></i>
                <p class="ml20 wordbreak"><a href="<?=\yii\helpers\Url::to(['/club/topic/expinfo','id'=>$value->exp_id])?>"><?=\yii\helpers\Html::encode($value->title)?></a></p>
                <div class="tr "><b class="red"><?=$value->total_click?></b> 阅读</div>
            </li>
        <?php } ?>
    </ul>
<?php } ?>