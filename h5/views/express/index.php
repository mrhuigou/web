<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/11
 * Time: 9:53
 */
$this->title="配送订单";
?>
<header class="fx-top bs-bottom whitebg lh44">
	<div class="flex-col tc">
		<a class="flex-item-2" href="/express/index">
			<i class="aui-icon aui-icon-home green f28"></i>
		</a>
		<div class="flex-item-8 f16">
			<?= \yii\helpers\Html::encode($this->title) ?>
		</div>
		<a class="flex-item-2 refresh_btn" href="/express/order" >
			<i class="aui-icon aui-icon-search green f28"></i>查询
		</a>
	</div>
</header>
<div class="mt50 pt50">
	<p class="tit--">输入提货券信息</p>
	<div class="m10">
		<input class="input appbtn tl w mb10" placeholder="输入卡号" type="text" id="card_no">

		<input class="input appbtn tl w mb10" placeholder="输入卡密" type="tel" id="card_pwd">

		<a class="btn lbtn w greenbtn" href="javascript:;" id="next_btn">下一步</a>
	</div>
    <?php if($model){?>
        <p class="tit--">最新5笔订单</p>
        <?php foreach ($model as $value){?>
    <a href="<?=\yii\helpers\Url::to(['/express/order-info','order_no'=>$value->order_code])?>">
            <div class="br5 m10 p10 bg-wh">
    <h2 class="clearfix"><span class="fl">订单号：<?=$value->order_code?></span><span class="fr org"><?=$value->status?$value->status->name:'提交成功'?></span></h2>
    <div class="flex-col bdb  lh150 bg-wh">
        <div class="flex-item-2 tc">编号</div>
        <div class="flex-item-8">名称规格</div>
        <div class="flex-item-2 tc">数量</div>
    </div>
                <?php foreach ($value->expressOrderProducts as $key=>$product){?>
                    <div class="flex-col p10  lh150 bg-wh">
                        <div class="flex-item-2 tc"><?=$key+1?></div>
                        <div class="flex-item-8"><?=$product->product_name?$product->product_name:$product->description?></div>
                        <div class="flex-item-2 tc">X <?=$product->quantity?></div>
                    </div>
                <?php }?>
            </div>
    </a>
            <?php }?>
<?php }?>
</div>
<?php $this->beginBlock('JS_INIT')?>
$("#next_btn").on('click',function () {
    $.showLoading("正在加载");
    $.post('/express/check-card',{card_no:$("#card_no").val(),card_pwd:$("#card_pwd").val()},function (res) {
        $.hideLoading();
        if(res.status){
            location.href="/express/check-order";
        }else{
            $.alert(res.message);
        }
    },'json')
})
<?php $this->endBlock() ?>

<?php
$this->registerJs($this->blocks['JS_INIT'], \yii\web\View::POS_END);
?>
