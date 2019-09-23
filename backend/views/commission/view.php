<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Exercise */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '分销管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
	<?=\backend\widgets\Customizer::widget();?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
				<?= Html::encode($this->title) ?>
            </h3>
			<?= \yii\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

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
    <!-- END PAGE HEADER-->
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_6_1" data-toggle="tab">基础信息</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_6_1">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'customer_id',
                    'username',
                    'telephone',
                    'commission',
                    'date_added',
                    'status',
                ],
            ]) ?>
            </div>
        </div>

</div>
