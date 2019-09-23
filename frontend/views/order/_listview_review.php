<table class=" w  ">
	<colgroup>
		<col width="30%"/>
		<col width="40%"/>
		<col width="30%"/>
	</colgroup>
	<tr>
		<td class="clearfix">
			<img src="<?= \common\component\image\Image::resize($model->product->image,45,55);?>" width="45" height="55" class="bd mr10 fl db">
			<p class="fl clearfix tl w220">
				<?=$model->orderProduct?$model->orderProduct->name:'';?> <br>
				单价：<span class="org"><?php echo number_format($model->orderProduct?$model->orderProduct->price:0,2);?></span>
			</p>
		</td>
			<td>
				<span class="gray6"><?=$model->author?></span>
				<p><?=\yii\helpers\Html::encode($model->text);?></p>
			</td>
		<td>
			<div class="clearfix"><span class="fl">商品：</span><div class="star fl" data-rating="<?=$model->rating?>"></div></div>
			<div class="clearfix"><span class="fl">服务：</span><div class="star fl" data-rating="<?=$model->service?>"></div></div>
			<div class="clearfix"><span class="fl">配送：</span><div class="star fl" data-rating="<?=$model->delivery?>"></div></div>
		</td>
	</tr>
</table>

