<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/26
 * Time: 15:36
 */
$models=[];
$discount=0;
$sub_total_old = 0;
$checkout_ad = 2;
$is_coupon = false;
$merge_orders=Yii::$app->session->get('can_merge_orders');
$isMerge=$merge_orders['isOrderMerge'];
$haveFreeCard=0;
if($isMerge){ //合单时
    foreach($model as $total){
        if($total['code']=='coupon'){
            $is_coupon = true;
            $couModel=\api\models\V1\Coupon::find()->where(['coupon_id' => $total['code_id']])->one();
            if($couModel->model=='ORDER' && $couModel->is_entity){
                $haveFreeCard=1;
                $models[$total['code']]=[
                    'title'=>'优惠券金额',
                    'value'=>0,
                    'code'=>'coupon',
                    'code_id'=>$total['code_id']
                ];
            }else{
                $discount=bcadd($discount,$total['value'],2);
                $models[$total['code']]=[
                    'title'=>'优惠券金额',
                    'value'=>number_format($discount,2,'.',''),
                    'code'=>'coupon',
                    'code_id'=>$total['code_id']
                ];
            }

        }else{
            $models[]=$total;
        }

        if($total['code'] == 'sub_total'){
            $sub_total_old = bcadd($sub_total_old,$total['value'],2);
        }

    }
}else{
    foreach($model as $total){
        if($total['code']=='coupon'){
            $is_coupon = true;
            $discount=bcadd($discount,$total['value'],2);
            $models[$total['code']]=[
                'title'=>'优惠券金额',
                'value'=>number_format($discount,2,'.',''),
                'code'=>'coupon',
                'code_id'=>$total['code_id']
            ];
        }else{
            $models[]=$total;
        }

        if($total['code'] == 'sub_total'){
            $sub_total_old = bcadd($sub_total_old,$total['value'],2);
        }

    }
}

?>
<?php if($haveFreeCard){ ?>
    <?php foreach ($models as $total) { ?>
        <?php if($total['code']=='sub_total'){?>
            <p class="mb5 clearfix lh150">
                <span class="fr red fb">￥<em class="<?=$total['code']?>"><?=$total['value']?></em></span>
                <span class="fl fb"><?=$total['title']?>：</span>
            </p>
            <p class="mb5 clearfix lh150">
                <span class="fr red fb">￥<em class="m-order-total"><?=$sub_total_old + $discount?></em></span>
                <span class="fl fb">订单金额：</span>
            </p>
            <div class="p5" id="free_return" style="display: none">
                <?php if( count($checkout_ad) < 1){?>
                    <a class="btn mbtn greenbtn-bd tc w" href="/read-more/index">满68包邮，去凑单</a>
                <?php }else{?>
                    <a class="btn mbtn greenbtn-bd tc w layerTri" href="javascript:void(0)">订单金额满68元免运费，去凑单</a>
                <?php }?>

            </div>
        <?php }?>
    <?php }?>
<?php }else{ ?>
    <?php foreach ($models as $total) { ?>

        <?php   if($total['code'] == 'shipping'){?>
            <?php if(empty($isMerge)) {?>
                <div style="border:1px dashed #000;margin-top: 30px;"></div>

                <p class="mb5 clearfix lh150">
                    <span class="fr red fb">￥<em class="coupon-sp-f <?=$total['code']?>"><?=$total['value']?></em></span>
                    <span class="fl fb"><?=$total['title']?>：</span>
                </p>
            <?php }?>
        <?php }else{?>

            <?php   if($total['code'] == 'coupon'){ ?>
                <p class="mb5 clearfix lh150">
                    <span class="fr red fb">￥<em class="<?=$total['code']?>"><?=$total['value']?></em></span>
                    <span class="fl fb"><?=$total['title']?>：</span>
                </p>
                <?php foreach($model as $v){ ?>
                    <p class="mb5 clearfix lh150">
                        <?php if($v['code']=='coupon'){
                            $coupon = \api\models\V1\Coupon::findOne(['coupon_id'=>$v['code_id']]);?>


                            <span class="fr grey ">(￥<em class=""><?= $v['value'];?>)</em></span>
                            <span class="fl ti2"><?= $coupon->name;?>：</span>

                        <?php  }?>
                    </p>
                <?php } ?>
                <p class="mb5 clearfix lh150">
                    <span class="fr red fb">￥<em class="m-order-total"><?=($sub_total_old + $discount)>0?$sub_total_old + $discount:0 ?></em></span>
                    <span class="fl fb">订单金额：</span>
                </p>
                <div class="p5" id="free_return" style="display: none">
                    <?php if( count($checkout_ad) < 1){?>
                        <a class="btn mbtn greenbtn-bd tc w" href="/read-more/index">满68包邮，去凑单</a>
                    <?php }else{?>
                        <a class="btn mbtn greenbtn-bd tc w layerTri" href="javascript:void(0)">订单金额满68元免运费，去凑单</a>
                    <?php }?>

                </div>
            <?php } else{?>

                <?php if(!$is_coupon && $total['code']=='sub_total'){?>
                    <p class="mb5 clearfix lh150">
                        <span class="fr red fb">￥<em class="<?=$total['code']?>"><?=$total['value']?></em></span>
                        <span class="fl fb"><?=$total['title']?>：</span>
                    </p>
                    <p class="mb5 clearfix lh150">
                        <span class="fr red fb">￥<em class="m-order-total"><?=$sub_total_old + $discount?></em></span>
                        <span class="fl fb">订单金额：</span>
                    </p>
                    <div class="p5" id="free_return" style="display: none">
                        <?php if( count($checkout_ad) < 1){?>
                            <a class="btn mbtn greenbtn-bd tc w" href="/read-more/index">满68包邮，去凑单</a>
                        <?php }else{?>
                            <a class="btn mbtn greenbtn-bd tc w layerTri" href="javascript:void(0)">订单金额满68元免运费，去凑单</a>
                        <?php }?>

                    </div>
                <?php }else{?>
                    <?php if(empty($isMerge)) {?>
                        <p class="mb5 clearfix lh150">
                            <span class="fr red fb">￥<em class="<?=$total['code']?>"><?=$total['value']?></em></span>
                            <span class="fl fb"><?=$total['title']?>：</span>
                        </p>
                    <?php }?>
                <?php }?>

            <?php } ?>
        <?php } ?>

    <?php } ?>
<?php } ?>


