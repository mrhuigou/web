<div class="bdb br5 whitebg p10 clearfix mb10">

    <?php
    $return_status_id = '';
    if($return = \api\models\V1\ReturnBase::find()->where(['order_id'=>$model->order_id])->all()){

        $return_status6 = false;//退货状态 （取消退货）  1.4.6
        $return_status1 = false;//退货状态 （待处理）
        $return_id = '';
        foreach ($return as $key => &$value){
            //待处理判断
            if($value->return_status_id == 1){
                $return_status1 = true;
                $return_id = $value->return_id;
                break;
            }
            //取消退货处理
            if($value->return_status_id == 6){
                $return_status6 = true;
                $return_id6 = $value->return_id;
            }else{
                $return_status6 = false;
                $return_id4 = $value->return_id;
                break;
            }
        }

        if(!$return_status1) {//不存在待处理
            if (!$return_status6 && !empty($return_id4)) {//完成退货
                $return_id = $return_id4;
            } else {
                $return_id = $return_id6;
            }
        }
        $return_info = \api\models\V1\ReturnBase::findOne(['return_id'=>$return_id]);
        $return_status_id = $return_info->returnStatus->return_status_id;
    }
    ?>
	<div class="fl f12 pw80">
		<p class="gray9"><?= $model->orderShipping->shipping_firstname; ?>：<?= $model->orderShipping->shipping_telephone; ?></p>
		<p class="gray9">订单号：<?= $model->order_id; ?></p>
        <p class="gray9">订单状态：<span class="red"><?= $model->orderStatus->name; ?></span><span class="red"><?= !empty($return_status_id) && in_array($return_status_id,[1]) ?'(退货处理中)':''; ?></span></p>
		<p class="gray9">下单日期：<?= $model->date_added; ?></p>
	</div>

	<div class="fr pw20 red f14 tr ">
        <?php if($model->order_status_id==11){?>
        <p>到账实收益</p>

            <?php if($type == 'aff_customer'){
                    $affiliate_transaction_flow = \api\models\V1\AffiliateTransactionFlow::findOne(['type'=>'order','type_id'=>$model->order_id]);
//                    $customer_commission_flow = \api\models\V1\CustomerCommissionFlow::findOne(['type'=>'order','type_id'=>$model->order_id,'aff_type'=>'aff_customer']);
                }else{
//                    $customer_commission_flow = \api\models\V1\CustomerCommissionFlow::findOne(['type'=>'order','type_id'=>$model->order_id,'aff_type'=>'aff_personal']);
                    $affiliate_transaction_flow = \api\models\V1\AffiliateTransactionFlow::findOne(['type'=>'order','type_id'=>$model->order_id]);
                }
                ?>
            <?php  if($affiliate_transaction_flow){?>
                <p>￥<?= $affiliate_transaction_flow->amount; ?></p>
                <?php }else{?>
                <p>￥0</p>
                <?php }?>

        <?php }else{?>
        <p>待收益</p>
            <?php $commision = $model->getOrderCommision(Yii::$app->user->identity->getAffiliateId()); ?>
           <?php if($type == 'aff_customer'){ ?>
                <p>￥<?= $commision['aff_affiliate_commision']; ?></p>
<!--                <p>￥--><?//= $commision['aff_customer_commision']; ?><!--</p>-->
                <?php }else{ ?>
                <p>￥<?= $commision['aff_personal_commision']; ?></p>
                <?php }?>

        <?php }?>
	</div>
</div>