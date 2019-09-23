<?php
/* @var $this yii\web\View */
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
$this->title = $model->order_id;
$this->params['breadcrumbs'][] = ['label' => '退货管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->registerCssFile('assets/css/pages/invoice.css',["depends" => "backend\assets\AppAsset"]);?>
<div class="page-content">

<!-- BEGIN STYLE CUSTOMIZER -->
<?=\backend\widgets\Customizer::widget();?>
<!-- END STYLE CUSTOMIZER -->
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            退货管理 <small>监控、统计、分析</small>
        </h3>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
    <div class="tabbable-custom ">
        <ul class="nav nav-tabs ">
            <li class="active">
                <a href="#tab_5_1" data-toggle="tab">退换货订单信息</a>
            </li>
            <li class="">
                <a href="#tab_5_2" data-toggle="tab">订单日志</a>
            </li>
            <li class="">
                <a href="<?=\yii\helpers\Url::to(['/order/view','id'=>$model->order_id])?>" target="_blank">原始订单信息</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_5_1">
                <div class="invoice">
                    <div class="row invoice-logo">
                        <div class="col-xs-6">
                           <h2>#<?=$model->return_code?></h2>
                        </div>
                        <div class="col-xs-6">
                            <p>
                                 <?=$model->returnStatus->name?>
                                <span class="muted">
								<?=$model->date_added?>
							</span>
                            </p>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-xs-3">
                            <h4>客户信息:</h4>
                            <ul class="list-unstyled">
                                <li>
	                                <?=$model->firstname?>
                                </li>
                                <li>
	                                <?=$model->telephone?>
                                </li>
                                <li>
	                                <?=$model->email?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-3">
                            <h4>退货类型:</h4>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="javascript:;" class="return_base_item" data-type="select" data-name="is_all_return"   data-pk="<?=$model->return_id?>" data-value="<?=$model->is_all_return?>",data-placement="right" data-placeholder="Required" data-original-title="选择">
                                        <?=$model->is_all_return?"全部":"部分"?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-3">
                            <h4>退货子类型:</h4>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="javascript:;" class="return_base_sub_item" data-type="select" data-name="return_method"   data-pk="<?=$model->return_id?>" data-value="<?=$model->return_method?>",data-placement="right" data-placeholder="Required" data-original-title="选择">
		                                <?php switch ($model->return_method){
			                                case "RETURN_GOODS":
				                                echo "退货";
				                                break;
			                                case "RESHIP":
				                                echo "换货";
				                                break;
			                                case "RETURN_PAY":
				                                echo "仅退款";
				                                break;
			                                default:
				                                echo "默认";
				                                break;
		                                }?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-3 invoice-payment">
                            <h4>原始订单</h4>
                            <ul class="list-unstyled">
                                <li>
		                            原订单号：<?=$model->order_id?>
                                </li>
                                <li>
	                                原订单支付方式：<?=$model->order->payment_method?><?=$model->order->payment_deal_no?>
                                </li>
                                <li>
                                    原订单金额：￥<?=$model->order->total?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <p>退货原因：<a href="javascript:;" class="return_base_comment_item" data-type="textarea" data-name="comment"   data-pk="<?=$model->return_id?>" data-placement="right" data-placeholder="Required" data-original-title="编辑"><?=$model->comment?></a></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        商品名称
                                    </th>
                                    <th >
                                        编码/规格
                                    </th>
                                    <th >
                                        数量
                                    </th>
                                    <th>
                                        小计
                                    </th>
                                    <th>
                                        备注
                                    </th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($model->returnProduct as $key=>$value){?>
                                <tr>
                                    <td>
                                        <?=$key+1?>
                                    </td>
                                    <td>
                                        <?=$value->name?><br/>
                                        <?=$value->from_table=="order_gift"?"【赠品】":''?>
                                    </td>
                                    <td >
	                                    <?=$value->product_code?><br/>
	                                    <?=$value->format?> --- <?=$value->unit?>
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="return_product_item" data-type="text" data-name="quantity" data-pk="<?=$value->return_product_id?>" data-placement="right" data-placeholder="Required" data-original-title="填写数量">
	                                        <?=$value->quantity?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="return_product_item" data-type="text" data-name="total" data-pk="<?=$value->return_product_id?>" data-placement="right" data-placeholder="Required" data-original-title="填写金额">
		                                    <?=$value->total?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="return_product_item" data-type="text" data-name="comment" data-pk="<?=$value->return_product_id?>" data-placement="right" data-placeholder="Required" data-original-title="填写金额">
			                                <?=$value->comment?>
                                        </a>
                                    </td>
                                    <td><a href="<?=\yii\helpers\Url::to(['/refund/deleteproduct','id'=>$value->return_product_id])?>" data-confirm="您确实要删除当前记录吗？">删除</a></td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                        </div>
                        <div class="col-xs-8 invoice-block">

                            <ul class="list-unstyled amounts">
	                            <?php if($model->totals){?>
                                <?php foreach ($model->totals as $value){?>
                                <li>
                                    <strong><?=$value->title?>:</strong>  <?=$value->text?> <a href="<?=\yii\helpers\Url::to(['/refund/delete-record','id'=>$value->id])?>" data-confirm="您确实要删除当前记录吗？">删除</a>
                                </li>
                                <?php } ?>
	                            <?php }?>
                                <li>
                                    <strong>合计:</strong>  <?=$model->total?>
                                </li>
                            </ul>
                            <a class="btn btn-lg default" data-toggle="modal"  href="#return_action">退单操作</a>
                            <a class="btn btn-lg default " data-toggle="modal" href="#total">添加其它流水</a>
                            <?php if($model->return_status_id==1){?>
                            <?php if(!$model->send_status){?>
                            <a class="btn btn-lg blue " href="javascript:;" id="tongbu" >同步后台</a>
                            <?php }else{?>
                            <a class="btn btn-lg default " href="javascript:;">已同步</a>
                            <?php }?>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane " id="tab_5_2">
                <table class="table table-striped table-bordered"><thead>
                    <tr><th>状态</th><th>内容</th><th>时间</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach($model->history as $value){?>
                    <tr ><td><?=$value->returnStatus->name?></td><td><?=$value->comment?></td><td><?=$value->date_added?></td></tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
    <div class="modal fade" id="total" tabindex="-1" role="total" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">添加流水</h4>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal" id="total_form">
                        <div class="form-group">
                            <label class="control-label col-md-2">标题</label>
                            <div class="col-md-10">
                                <input type="text" value=""  class="form-control" name="title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">类型</label>
                            <div class="col-md-10">
                                <select class="form-control" name="type">
                                    <option value="sub_total">商品金额</option>
                                    <option value="shipping">运费</option>
                                    <option value="coupon">优惠券</option>
                                    <option value="other">其它</option>
                                    <option value="total">订单金额</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">金额</label>
                            <div class="col-md-10">
                                <input type="text" value=""  class="form-control" name="value">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a  href="javascript:;" class="btn default" data-dismiss="modal">取消</a>
                    <a class="btn blue" href="javascript:;" id="submit">提交</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="return_action" tabindex="-1" role="return_action" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">添加记录</h4>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal" id="return_action_form">
                        <div class="form-group">
                            <label class="control-label col-md-2">状态</label>
                            <div class="col-md-10">
                                <?php if($status=\api\models\V1\ReturnStatus::find()->all()){?>
                                <select class="form-control" name="return_status_id">
                                    <?php foreach ($status as $value){?>
                                    <option value="<?=$value->return_status_id?>"><?=$value->name?></option>
                                    <?php } ?>
                                </select>
                                <?php }?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">备注</label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="comment"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a  href="javascript:;" class="btn default" data-dismiss="modal">取消</a>
                    <a class="btn blue" href="javascript:;" id="return_action_submit">提交</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
//editables element samples
$('.return_product_item').editable({
inputclass: 'form-control',
url: '<?=\yii\helpers\Url::to(['/refund/product-update'])?>',
type: 'text',
ajaxOptions: {
success:function(data){
location.reload();
}
}
});
$('.return_base_comment_item').editable({
inputclass: 'form-control',
url: '<?=\yii\helpers\Url::to(['/refund/update'])?>',
type: 'text'
});
$('.return_base_item').editable({
inputclass: 'form-control',
url: '<?=\yii\helpers\Url::to(['/refund/update'])?>',
source: [{
value: 0,
text: "部份"
}, {
value: 1,
text: "全部"
}
]
});
$('.return_base_sub_item').editable({
inputclass: 'form-control',
url: '<?=\yii\helpers\Url::to(['/refund/update'])?>',
source: [{
value: 'RETURN_GOODS',
text: "退货"
}, {
value: 'RESHIP',
text: "换货"
}, {
value: 'RETURN_PAY',
text: "仅退款"
}
]
});
$("#submit").on('click',function(){
$.post('<?=\yii\helpers\Url::to(['/refund/addrecord','id'=>$model->return_id])?>',$("#total_form").serialize(),function(data){
location.reload();
});
});
$("#return_action_submit").on('click',function(){
$.post('<?=\yii\helpers\Url::to(['/refund/history','id'=>$model->return_id])?>',$("#return_action_form").serialize(),function(data){
location.reload();
});
});
$("#tongbu").on('click',function(){
if(confirm("您确定要同步当前订单到后台吗？")){
$.get('<?=\yii\helpers\Url::to(['/refund/send','id'=>$model->return_id])?>',function(data){
location.reload();
});
}

});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>

