<div class="order_item mb10">
    <h2 class="p10  bdb whitebg"> <span class="vm">编号：<?=$model->order_no;?></span>       <span class="vm fr org"> <?=$model->orderStatus->name;?> </span></h2>
    <a class="db w" href="<?=\yii\helpers\Url::to(['/order/info','order_no'=>$model->order_no],true)?>">
    <?php if($model->orderProducts){ ?>
    <?php foreach($model->orderProducts as $order_proudct){ ?>
    <table class="bg-wh tbp5 w" >
        <tr >
            <td width="30%">
                <img src="<?=\common\component\image\Image::resize($order_proudct->product->image,100,100)?>"  class="bd w" >
            </td>
        <td valign="top">
            <h2 class="row-two"><?=$order_proudct->name;?></h2>
            <p class="gray9  mt2"><?=$order_proudct->product->getSku();?></p>
            <?=h5\widgets\Order\Promotion::widget(['product'=>$order_proudct])?>
        </td>
        <td width="20%" class="tc">
            <p class="gray6 mb5">X <em class="qty"><?= $order_proudct->quantity; ?></em></p>
            <p class="red mb5"><em class="f12">实付</em>￥<?= floatval($order_proudct->pay_total) ?></p>
        </td>
        </tr>
    </table>
    <?php }?>
        <?=h5\widgets\Order\OrderPromotion::widget(['order'=>$model])?>
    </a>
    <div class="p10 tr bdb  whitebg">
        <span >共<?=$model->orderProductQty?>件商品  合计：￥<?= number_format($model->total,2);?> </span>
    </div>
<div class="p5 tr    whitebg">
    <?=\h5\widgets\Order\OrderMenu::widget(['model'=>$model])?>
</div>
    <?php } ?>
    <?php if($model->orderDigitalProducts){?>
    <?php foreach ($model->orderDigitalProducts as $op) { ?>
            <div class="flex-col flex-center store-item bdb  whitebg" >
                <div class="flex-item-2 flex-row flex-middle flex-center p5">
                    <i  class="iconfont red f50 lh44">&#xe628;</i>
                </div>
                <div class="flex-item-6 flex-row  flex-left item-info p5">
                    <h2><?= $op->model; ?></h2>
                    <p class="gray9"><?= $op->name; ?> </p>
                    <?php if($op->type=='telephone'){ ?>
                        <p class="gray9"><?= $op->account; ?> </p>
                    <?php } ?>
                </div>
                <div class="flex-item-4 flex-row flex-middle flex-center item-price">
                    <p >￥<?php echo number_format($op['price'], 2); ?></p>
                    <p class="gray6">x <em class="qty"><?php echo $op['qty']; ?></em></p>
                </div>
            </div>
        <?php }?>
        <div class="p10 tr bdb  whitebg">
            <span >合计：￥<?= number_format($model->total,2);?> </span>
        </div>
        <div class="p5 tr    whitebg">
	        <?=\h5\widgets\Order\OrderMenu::widget(['model'=>$model])?>
        </div>
    <?php } ?>
</div>

