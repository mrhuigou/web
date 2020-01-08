<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\ShareLogoScansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分享场景二维码';
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
        <?= Html::a('创建 图片logo内容', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'share_logo_scans_id',
            'weixin_scans_id',
            'scan.title',
            'title',
            'type',
            'parameter',
            'logo_url:url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
