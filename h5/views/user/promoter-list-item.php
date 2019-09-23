<div class="bdb br5 whitebg p10 clearfix mb10">
	<div class="fl f12 pw80">
		<p class="gray9">订单号：<?= $model->order_no; ?></p>
        <p class="gray9">订单状态：<span class="red"><?= $model->orderStatus->name; ?></span></p>
		<p class="gray9">下单日期：<?= $model->date_added; ?></p>
	</div>
	<div class="fr pw20 red f14 tr ">
        <p>佣金</p>
		<p>￥<?= bcmul($model->total,0.03,2); ?></p>
	</div>
</div>