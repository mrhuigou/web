<div class="bdb br5 whitebg p10 clearfix mb10">
	<div class="fl f12 pw80">
		<p class="gray9">订单号：<?= $model->order_no; ?></p>
        <p class="gray9">订单状态：<span class="red"><?= $model->orderStatus->name; ?></span></p>
		<p class="gray9">下单日期：<?= $model->date_added; ?></p>
	</div>

	<div class="fr pw20 red f14 tr ">
        <?php if($model->order_status_id==11){?>
        <p>到账实收益</p>

            <?php if($type == 'aff_customer'){
                    $customer_commission_flow = \api\models\V1\CustomerCommissionFlow::findOne(['type'=>'order','type_id'=>$model->order_id,'aff_type'=>'aff_customer']);
                }else{
                    $customer_commission_flow = \api\models\V1\CustomerCommissionFlow::findOne(['type'=>'order','type_id'=>$model->order_id,'aff_type'=>'aff_personal']);
                }
                ?>
            <?php  if($customer_commission_flow){?>
                <p>￥<?= $customer_commission_flow->amount; ?></p>
                <?php }else{?>
                <p>￥0</p>
                <?php }?>

        <?php }else{?>
        <p>待收益</p>
            <?php $commision = $model->getOrderCommision(Yii::$app->user->getId()); ?>
           <?php if($type == 'aff_customer'){ ?>
                <p>￥<?= $commision['aff_customer_commision']; ?></p>
                <?php }else{ ?>
                <p>￥<?= $commision['aff_personal_commision']; ?></p>
                <?php }?>

        <?php }?>
	</div>
</div>