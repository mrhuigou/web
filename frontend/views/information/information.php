<?php
$this->title =  $information->description->title.'- 家润慧生活（mrhuigou.com）- 青岛首选综合性同城网购-发现达人体验-分享同城生活';;
?>
<div class="" style="min-width:1100px;">
    <div class="w1100 bc pt10 tshop-pbsm-tmall-srch-list">

        <!--面包屑导航-->
        <div class="user_center clearfix">
<!--            <dl class="w168 fl userCen_sidebar nobg whitebg">-->
<!--                --><?php //foreach($types as $type) { ?>
<!--                    <dt>--><?php //echo $type['name'];?><!--</dt>-->
<!--                    --><?php //foreach($type['cms'] as $cms) { ?>
<!--                        --><?php //if(Yii::$app->request->get("information_id") == $cms->information_id) { ?>
<!--                            <dd><a href="--><?php //echo yii\helpers\Url::to(["information/information","information_id"=>$cms->information_id]);?><!--" class="current" ><strong>--><?php //echo $cms->description->title;?><!--</strong></a></dd>-->
<!--                        --><?php //}else { ?>
<!--                            <dd><a href="--><?php //echo yii\helpers\Url::to(["information/information","information_id"=>$cms->information_id]);?><!--">--><?php //echo $cms->description->title;?><!--</a></dd>-->
<!--                        --><?php //} }?>
<!--                --><?php //} ?>
<!--            </dl>-->

            <div class="w1100 whitebg fr" >
                <h2 class="titStyle3 f14 graybg2"><?php echo $information->description->title; ?></h2>
                <style>
                    .max_imgwidth img { max-width:860px;}
                </style>
                <div class="p30 lh200 max_imgwidth" >
                    <?php echo  html_entity_decode($information->description->description); ?>
                </div>
            </div>
        </div>
    </div>
</div>