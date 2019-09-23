<?php
use yii\helpers\Html;
?>
<tr data-key="6">
	<td><?=$model->model?></td>
	<td><?=$model->name?></td>
	<td><?=$model->account?></td>
	<td><?=$model->price?></td>
	<td><?=$model->qty?></td>
	<td><?=$model->total?></td>
	<td><?=$model->status?'成功':'失败'?></td>
	<td><?=$model->callback_data?></td>
</tr>
