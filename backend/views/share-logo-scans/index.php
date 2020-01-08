<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\ShareLogoScansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分享场景二维码';
$this->params['breadcrumbs'][] = $this->title;
$logo_type = array(
    1 => '默认',
    2 => '促销方案',
    3 => '商品详情',
    4 => '页面专题',
);
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
            'description:ntext',
//            'type',
            [
                'attribute' => 'type',
                'value' => function($model){
                    $logo_type = array(
                        1 => '默认',
                        2 => '促销方案',
                        3 => '商品详情',
                        4 => '页面专题',
                    );
                    return $logo_type[$model->type];
                },
                'filter' => Html::activeDropDownList($searchModel,
                    'type',
                    $logo_type,
                    [
                        'prompt' => '全部',
                        'class' => 'form-control'
                    ]
                )

            ],
            'parameter',
            'logo_url:url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
