<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/14
 * Time: 14:44
 */
?>
<?php if($model->orderProducts){ ?>
<?php if($model->order_status_id == 1){ ?>
	<a class="btn sbtn orgbtn pr10 pl10" href="<?=\yii\helpers\Url::to(['/order/add-cart','order_no'=>$model->order_no],true)?>" > 编辑 </a>
	<a class="btn sbtn redbtn pr10 pl10" href="<?=\yii\helpers\Url::to(['/order/pay','order_no'=>$model->order_no],true)?>" > 支付 </a>
	<?= \yii\helpers\Html::a('取消订单', ['cancel', 'order_no' => $model->order_no], [
		'class' => 'btn sbtn graybtn pr10 pl10 ',
		'data' => [
			'confirm' => '您确认要取消吗？',
			'method' => 'post',
		],
	])?>
<?php }else{?>
<!--        --><?php //if($return = \api\models\V1\ReturnBase::findOne(['order_id'=>$model->order_id])){ ?>
<!--            <span class="vm fl">退货：<i class="org">--><?php //echo $return->returnStatus->name;?><!--</i></span>-->
<!--        --><?php //}?>

        <?php if($return = \api\models\V1\ReturnBase::find()->where(['order_id'=>$model->order_id])->all()){?>
            <?php
                $return_status = false;//退货状态 （取消退货）
                $returnStatusName = '';
                foreach ($return as $key => &$value){
                    if($value->return_status_id == 6){
                        $return_status = true;
                        continue;
                    }else{
                        $return_status = false;
                        $returnStatusName = $value->returnStatus->name;//die;
                    }

                }
                if($return_status && empty($returnStatusName)){
                    $returnStatusName = "取消退货";
                }
            ?>
            <span class="vm fl">退货：<i class="org"><?php echo $returnStatusName;?></i></span>
        <?php }?>
	<?php if(in_array($model->order_status_id,[2,3,5,9])){?>
		<a class="btn sbtn bluebtn pr10 pl10" href="<?=\yii\helpers\Url::to(['/order/shipping','order_no'=>$model->order_no],true)?>" > 查看物流 </a>
	<?php } ?>
	<?php if($model->order_status_id==10 && !$model->orderDelieryComment){?>
		<a class="btn sbtn bluebtn pr10 pl10" href="<?=\yii\helpers\Url::to(['/order/delivery','order_no'=>$model->order_no],true)?>" > 物流评价 </a>
		<?php }?>
        <?php if($model->orderDelieryComment){?>
        <a class="btn sbtn graybtn pr10 pl10" href="javascript::" > 已评价 </a>
        <?php }?>
<!--	<a class="btn sbtn orgbtn pr10 pl10" href="--><?//=\yii\helpers\Url::to(['/order/add-cart','order_no'=>$model->order_no],true)?><!--" > 再次购买 </a>-->
	<a class="btn sbtn graybtn pr10 pl10" href="<?=\yii\helpers\Url::to(['/order/info','order_no'=>$model->order_no],true)?>" > 订单详情 </a>


<?php }?>
<?php } ?>
<?php if($model->orderDigitalProducts){?>
	<?php if($model->order_status_id == 1){ ?>
		<a class="btn sbtn redbtn pr10 pl10" href="<?=\yii\helpers\Url::to(['/order/pay','order_no'=>$model->order_no],true)?>" > 支付 </a>
		<?= \yii\helpers\Html::a('取消订单', ['cancel', 'order_no' => $model->order_no], [
			'class' => 'btn sbtn graybtn pr10 pl10 ',
			'data' => [
				'confirm' => '您确认要取消吗？',
				'method' => 'post',
			],
		])?>
	<?php }else{?>
		<a class="btn sbtn graybtn pr10 pl10" href="<?=\yii\helpers\Url::to(['/order/info','order_no'=>$model->order_no],true)?>" > 查看详情 </a>
	<?php }?>
<?php } ?>
