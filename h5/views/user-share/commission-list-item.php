<div class="bdb br5 whitebg p10 clearfix mb10">
	<div class=" f12 w clearfix">

		<p class="fb fl lh200"><?= $model->title; ?></p>
        <p class="gray9 fr lh200"><span class="red"><?= floatval($model->amount); ?>元</span></p>
        <div class="clear"></div>
        <p class=" fl lh200">订单编号：</p>

        <?php if($model->type == 'order'){
            if($model->type_id){
                $order = \api\models\V1\Order::findOne(['order_id'=>$model->type_id]);?>
                <p class="gray9 fr lh200"><?= $order->order_no; ?></p>
                <?php
            }
        }?>

	</div>

    <div class=" f12 w clearfix">
    <p class=" fl lh200"><?= $model->remark; ?></p>
    <p class="gray9 fr lh200">日期：<?= date('m-d H:i:s',$model->create_at); ?></p>
    </div>
</div>