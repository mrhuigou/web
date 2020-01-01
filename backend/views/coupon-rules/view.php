<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Lottery */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coupon_rules_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coupon_rules_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coupon_rules_id',
            'name',
            'img_url',
            'start_time',
            'end_time',
            'user_total_limit',
        ],
    ]) ?>
    <?= Html::a('创建奖项', ['/coupon-rules/detail-create', 'coupon_rules_id' => $model->coupon_rules_id], ['class' => 'btn btn-primary']) ?>
    <table class="table table-striped table-bordered"><thead>
        <tr>
            <th>详情ID</th>
            <th>详情名称</th>
            <th>详情图片</th>
            <th>对应折扣券ID</th>
            <th>排序</th>
            <th ></th>
        </tr>
        </thead>
        <tbody>
        <?php if($details){?>
            <?php foreach ($details as $detail){?>
                <tr>
                    <td><?php echo $detail->coupon_rules_detail_id;?></td>
                    <td><?php echo $detail->name;?></td>
                    <td><?php echo $detail->img_url;?></td>
                    <td><?php echo $detail->coupon_id;?></td>
                    <td><?php echo $detail->sort;?></td>
                    <td><a href="<?php echo \yii\helpers\Url::to(['coupon-rules/detail-update','id'=>$detail->coupon_rules_detail_id]);?>">[更新]</a></td>
                </tr>
            <?php }?>
        <?php }?>
        </tbody></table>

</div>
