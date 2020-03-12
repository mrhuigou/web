<div class="bdb whitebg p10 clearfix">
	<div class="fl pw80">
		<p class="f14 mxh14 mb5"><?= $model->description; ?></p>
		<span class="gray9"><?= $model->date_added; ?></span>
	</div>
	<div class="fr pw20 gray6 f16 tr mt10 <?= $model->amount < 0 ? 'red' : ''; ?>">￥<?= $model->amount; ?></div>
</div>