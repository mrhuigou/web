<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/1
 * Time: 17:33
 */?>
<?php if($model->order_status_id == 1){ ?>
   <p> <a class="red fb " href="<?=\yii\helpers\Url::to(['/order/pay','order_no'=>$model->order_no],true)?>" > 立即支付 </a></p>
    <p>
        <a href="<?=\yii\helpers\Url::to(['order/info','order_no'=>$model->order_no],true)?>" class="gray9">订单详情</a>
    </p>
<p><?= \yii\helpers\Html::a('取消订单', ['cancel', 'order_no' => $model->order_no], [
        'class' => 'gray ',
        'data' => [
            'confirm' => '您确认要取消吗？',
            'method' => 'post',
        ],
    ])?></p>
<?php }else{?>
    <h2 class="fb "><?= $model->orderStatus->name; ?></h2>
    <p>
        <a href="<?=\yii\helpers\Url::to(['order/info','order_no'=>$model->order_no],true)?>" class="gray9">订单详情</a>
    </p>
    <?php if(in_array($model->order_status_id,[10,11])){?>
       <p> <a class="green" href="<?=\yii\helpers\Url::to(['/order/review','trade_no'=>$model->order_no],true)?>" > 评价 </a></p>
    <?php } ?>
    <?php if(in_array($model->order_status_id,[2,3,5,9,10,11,13])){?>
<p>   <a class="blue" href="<?=\yii\helpers\Url::to(['/order/shipping','order_no'=>$model->order_no],true)?>" > 查看物流 </a></p>
    <?php } ?>
<?php }?>

