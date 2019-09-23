<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
$this->title ='申请退货';
?>
<?=h5\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
    <?php $order_status = Yii::$app->request->get('order_status');?>
    <?php $form = ActiveForm::begin(['id' => 'form-signup','action'=>'/order/returnaction','fieldConfig' => [
        'template' => "<div class='pr pt-15em'>{input}<p class=\"input-setup clearfix\"><a href=\"javascript:void(0);\" class=\"input-del fr\" style=\"display:none;\"></a></p></div>{error}",
        'inputOptions' => ['class' => 'input-text w',"autocomplete"=>"off"],
        'errorOptions'=>['class'=>'error db']
    ],  ]);?>

    <label class="p10 db bdb whitebg"> <span class="vm">订单编号：<?=$order->order_no;?></span>       <span class="vm fr"> <?=$order->orderStatus->name;?> </span></label>

    <table cellspacing="0" cellpadding="0" class="tb-p10 w mb10 whitebg">
        <?php foreach($order->orderProducts as $order_proudct){ ?>

            <tr id="<?=$order_proudct->order_product_id?>">
                <td width="5%" class="bdb tc">
                    <span class=" tl mt2">
                         <?php $model->orderproduct = $order_proudct->order_product_id;?>

                         <?= $form->field($model, 'orderproduct')->checkbox(['value'=>$order_proudct->order_product_id,'name'=>'ReturnForm[orderproduct][]'],false) ?>
                </td>
                <td width="15%" class="bdb tc">

                        <img class="lazy" src="<?= \common\component\image\Image::resize($order_proudct->product->image,100,100);?>" width="50"  alt="tu" class="db w">

                </td>
                <td width="31%" class="bdb tl">
                   <?=$order_proudct->product->description->name;?><br>
                        <em class="green"><?=$order_proudct->product->getSku();?></em>

                </td>
                <td width="15%" class="bdb tc">
                    <span class="red tc mt2">￥<?=number_format($order_proudct->price,2,'.','')?></span>
                </td>
                <td  class="bdb tc">
                    <span class=" tl mt2">x<?= $order_proudct->quantity;?></span>
                </td>

            </tr>

        <?php }?>

    </table>
	<ul class="line-book">
		<li>
			<span>问题描述：</span>
			 <?= $form->field($model, 'comment')->textarea() ?>
             <?= $form->field($model, 'order_id')->input("hidden",['value'=>$order->order_id]) ?>
		</li>
		<li>
			<span>手机号码：</span>
			 <?= $form->field($model, 'telephone') ?>
		</li>
		<li>
			<span>是否开封：</span>
			<?php $model->opened = 0;?>
                    <?= $form->field($model, 'opened')->radioList(['0'=>'未开封','1'=>'开封']); ?>
		</li>
		<li>
			<span>退款方式：</span>
			<?php $model->paymethod = 1;?>
                <?= $form->field($model, 'paymethod')->radioList(['1'=>'原支付方式返回']); ?>
		</li>
		<li>
			<span>联系人姓名：</span>
			 <?= $form->field($model, 'firstname') ?>
		</li>
		  <?= Html::submitButton('提交申请', ['class' => 'appbtn mt-15em w greenbtn', 'name' => 'login-button']) ?>
	</ul>
			
    <?php ActiveForm::end(); ?>
</section>