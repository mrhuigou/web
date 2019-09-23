<?php
use yii\widgets\Breadcrumbs;

?>

<div class="graybg2 pb10" style="min-width:1100px;">
    <div class="w1100 bc pt15">
        <!--面包屑导航-->
        <?php
        $this->title = '我的消息';
        $this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
        $this->params['breadcrumbs'][] = $this->title;
        ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'tag'=>'p',
            'options'=>['class'=>'gray6 mb15'],
            'itemTemplate'=>'<a class="f14">{link}</a> > ',
            'activeItemTemplate'=>'<a class="f14">{link}</a>',
        ]) ?>
        <div class="user_center clearfix whitebg">
                <div class="bd">
                    <h3 class="bdb p10 pl20 mb10 f18"><?php echo $model->content;?></h3>
                    <div style="min-height: 300px;">

                            <?php if($model->messageContent){
                                $message_content = $model->messageContent;
                                if($message_content->type =='NEWS'){
                                    echo html_entity_decode($message_content->description);
                                }elseif($message_content->type =='PACK'){

                                }elseif($message_content->type =='URL'){

                                }
                            }else{
                                echo $model->content;
                            }?>

                    </div>
                </div>

        </div>
    </div>
</div>