<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ="消息";
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
    <section class="veiwport  mb50" >
        <div class="p5 whitebg mt10">
            <p class=" fb tc f14 lh200 ">
                <?=$model->content?>
            </p>
            <p class="tc"><?=$model->date_added?></p>
            <div class="lh200 w clearfix oh"><?php if($model->messageContent){
                    if($model->messageContent->type =='NEWS'){
                        ?>

                        <?=Html::decode($model->messageContent->description)?>
                 <?php
                    }elseif($model->messageContent->type =='PACK'){

                    }elseif($model->messageContent->type =='URL'){

                    }
                }else{
                    echo $model->content;
                }?>
            </div>
        </div>
    </section>
<?=h5\widgets\MainMenu::widget();?>