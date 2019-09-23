<?php
use yii\helpers\Html;
?>
<table class="bdt bdl bdr w mt10 ">
    <colgroup>
        <col width="40%"/>
        <col width="10%"/>
        <col width="10%"/>
        <col width="10%"/>
        <col width="15%"/>
        <col width="15%"/>
    </colgroup>
    <tr>
        <th colspan="6" class="tl p5 graybg clearfix">
			<span class="  ml5 vm">
				订单编号：<?= $model->order_no; ?> &nbsp;&nbsp;&nbsp;&nbsp; 下单时间：<?= $model->date_added; ?>
			</span>
            <span class=" fr ml5 vm">
				<?php if(in_array($model->order_type_code,['normal','presell'])){ ?><a class="org fb " href="<?=\yii\helpers\Url::to(['/order/add-cart','order_no'=>$model->order_no])?>">再次购买</a><?php }?>
			</span>
        </th>
    </tr>
    <tr>
        <td colspan="4">
            <?php if($model->orderProducts){ ?>
            <?php foreach ($model->orderProducts as $op) { ?>
                <table class="bdb p10 w">
                    <colgroup>
                        <col width="40%"/>
                        <col width="10%"/>
                        <col width="10%"/>
                        <col width="10%"/>
                    </colgroup>
                    <tr>
                        <td>
                            <div class="p5 fl">
                                <a  href="<?= \yii\helpers\Url::to(['product/index', 'shop_code' => $model->store->store_code, 'product_code' => $op->product_code], true) ?>">
                                    <img src="<?= \common\component\image\Image::resize($op->product->image, 55, 55); ?>"  width="55"  height="55"/>
                                </a>
                            </div>
                            <div class="p5">
                                <h2><a  href="<?= \yii\helpers\Url::to(['product/index', 'shop_code' => $model->store->store_code, 'product_code' => $op->product_code], true) ?>">
                                    <?= $op->name; ?></a></h2>
                                <p class="gray9">规格：<?php echo $op['unit'] . ($op['format'] ? ('/' . $op['format']) : ''); ?></p>
                                <p><?=$op->sku_name?></p>
                                <?=frontend\widgets\Order\Promotion::widget(['product'=>$op])?>
                            </div>
                        </td>
                        <td class="bdl bdb org tc">
                            <?php echo number_format($op['price'], 2); ?>
                        </td>
                        <td class="bdl bdb tc"> <?php echo $op['quantity']; ?></td>
                        <td class="bdl bdb tc">
                            <p><a class="blue" href="javascript:;" onclick="addToWishList(<?=$op->product_base_id?>, <?=$model->store_id?>,'product')">商品收藏</a></p>
                            <?php if(!$op->review && in_array($model->order_status_id,[10,11])){?>
                                <p><a class="blue" href="<?=\yii\helpers\Url::to(['/order/review-form','order_no'=>$model->order_no,'item_id'=>$op->order_product_id])?>">商品评价</a></p>
                            <?php } ?>
                <?php if(in_array($model->order_status_id,[2,3,5,10,13])){?>
                            <p><a class="blue" href="<?=\yii\helpers\Url::to(['/order/refund','order_no'=>$model->order_no,'item_id'=>$op->order_product_id])?>">申请退换货</a></p>
                <?php } ?>
                        </td>
                    </tr>
                </table>
            <?php } ?>
            <?php } ?>
            <?php if($model->orderDigitalProducts){?>
            <?php foreach ($model->orderDigitalProducts as $op) { ?>
                    <table class="bdb p10 w">
                        <colgroup>
                            <col width="40%"/>
                            <col width="10%"/>
                            <col width="10%"/>
                            <col width="10%"/>
                        </colgroup>
                        <tr>
                            <td>
                                <div class="p5 fl tc " style="width: 50px;height: 50px;">
                                   <i class="iconfont f40 red vm" style="line-height: 100%"> &#xe635;</i>
                                </div>
                                <div class="p5">
                                    <h2><?= $op->model; ?></h2>
                                    <p class="gray9"><?= $op->name; ?> </p>
                                    <?php if($op->type=='telephone'){ ?>
                                    <p class="gray9"><?= $op->account; ?> </p>
                                    <?php } ?>
                                </div>
                            </td>
                            <td class="bdl org tc">
                                <?php echo number_format($op['price'], 2); ?>
                            </td>
                            <td class="bdl  tc"> <?php echo $op['qty']; ?></td>
                            <td class="bdl  tc"><span class="<?php echo ($op['status'] == 1)?'green':'org';?>">
						<?php echo ($op['status'] == 1)?'已充值':'待充值';?>
						</span></td>
                        </tr>
                    </table>
                <?php } ?>
            <?php } ?>
        </td>
        <td class="bdl bdb tc fb"><?php echo number_format($model->total, 2); ?></td>
        <td class="bdl bdb tc">
            <?=frontend\widgets\Order\OrderAction::widget(['order'=>$model])?>
        </td>
    </tr>
    <tr>
        <td colspan="6" >
            <?=frontend\widgets\Order\OrderPromotion::widget(['order'=>$model])?>
        </td>
    </tr>
</table>
