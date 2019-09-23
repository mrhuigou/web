<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Lottery */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'title',
            'description:ntext',
            'start_time',
            'end_time',
            'chances_per_customer',
        ],
    ]) ?>
    <?= Html::a('创建奖项', ['/lottery-prize/create', 'lottery_id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <table class="table table-striped table-bordered"><thead>
        <tr>
            <th>活动标题</th>
            <th>活动描述</th>
            <th>奖项图片</th>
            <th>基础数量</th>
            <th >概率</th>
            <th >指针角度</th>
            <th >对应折扣券</th>
            <th ></th>
            </tr>
        </thead>
        <tbody>
        <?php if($lottery_prizes){?>
            <?php foreach ($lottery_prizes as $lottery_prize){?>
                <tr>
                    <td><?php echo $lottery_prize->title;?></td>
                    <td><?php echo $lottery_prize->description;?></td>
                    <td><?php echo $lottery_prize->image;?></td>
                    <td><?php echo $lottery_prize->quantity;?></td>
                    <td><?php echo $lottery_prize->probability;?></td>
                    <td><?php echo $lottery_prize->angle;?></td>
                    <td><?php echo $lottery_prize->coupon_id;?></td>
                    <td><a href="<?php echo \yii\helpers\Url::to(['lottery-prize/update','id'=>$lottery_prize->id]);?>">[更新]</a></td>
                </tr>
            <?php }?>
        <?php }?>
        </tbody></table>

</div>
