<li class="pb10">
    <p class="mb10">
        <a href="<?=\yii\helpers\Url::to(['/club-try/detail',"id"=>$model->try_id])?>" class="fb"><?=$model->try->title?></a>
    </p>
    <p class="mb10 clearfix">
        <span class="fl">活动时间：<?=date('m/d H:i',strtotime($model->try->begin_datetime))?> 到 <?=date('m/d H:i',strtotime($model->try->end_datetime))?></span>
        <span class="fr">状态：<?php if($model->try->lottery_status ==1){
                if($model->status==1){
                    echo "已中奖";
                }else{
                    echo "未中奖,谢谢参与";
                }
            }else{
                echo "已报名";
            }?>

          </span>
    </p>
    <a href="<?=\yii\helpers\Url::to(['/club-try/detail',"id"=>$model->try_id])?>">
        <img src="<?=\common\component\image\Image::resize($model->try->image,640,270)?>" alt="" class="db mb10 w">
    </a>

</li>