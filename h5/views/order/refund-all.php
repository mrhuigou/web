<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
$this->title ='申请整单售后';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
    <?php $form = ActiveForm::begin(['id' => 'form-signup','fieldConfig' => [
        'template' => "<div class='bg-wh bdb p10 mb10'>{label}<div class='mt10'>{input}</div>{error}</div>",
        'inputOptions' => ['class' => 'input-text w mt5'],
        'labelOptions' => ['class' => 'p5 bdb db w f14'],
        'errorOptions'=>['class'=>'error db']
    ],  ]);?>
    <label class="p10 db bdb whitebg"> <span class="vm">订单编号：<?=$order->order_no;?></span><span class="vm fr"> <?=$order->orderStatus->name;?> </span></label>
    <?php foreach($order->orderProducts as $order_product){
        if($order_product->getRefundQty()==$order_product->quantity){
            continue;
        }
        ?>
        <div class="m5">
            <table class="w tbp5  bg-wh">
                <tr>
                    <td width="30%">
                        <img src="<?=\common\component\image\Image::resize($order_product->product->image,100,100)?>"  class="bd w" >
                    </td>
                    <td valign="top">
                        <h2 class="row-two"><?=$order_product->name;?></h2>
                        <p class="gray9  mt2"><?=$order_product->sku_name;?></p>
					    <?=h5\widgets\Order\Promotion::widget(['product'=>$order_product])?>
                    </td>
                    <td style="text-align: center;" width="20%">
                        x<?= $order_product->quantity; ?></br>
                        ￥<?=number_format($order_product->pay_total,2,'.','')?>
                    </td>
                </tr>
            </table>
        </div>
    <?php }?>
    <div class="whitebg bdb p10 mb10">
<!--	--><?//=$form->field($model,'return_model')->dropDownList(['RETURN_GOODS'=>'退货','RESHIP'=>'换货','RETURN_PAY'=>'仅退款'])?>
<!--	--><?//=$form->field($model,'return_model')->dropDownList(['RETURN_GOODS'=>'退货'])?>
	<?= $form->field($model, 'comment')->textarea(['class'=>'textarea w'])?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'telephone') ?>
<!--    --><?//= $form->field($model, 'paymethod')->radioList(['0'=>'退回账户余额','1'=>'原支付方式返回']); ?>
<!--    --><?//= $form->field($model, 'paymethod')->radioList(['1'=>'原支付方式返回']); ?>
    <?= Html::submitButton('提交申请', ['class' => 'btn lbtn  w greenbtn', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</section>