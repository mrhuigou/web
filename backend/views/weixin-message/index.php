<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use \Yii;


/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\WeixinMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Weixin Messages';
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
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-toolbar">
        <div class="btn-group">
            <?= Html::a('返回', "javascript:history.go(-1);", ['class' => 'btn btn-primary']) ?>
            <?= Html::a('创建消息 <i class=\"fa fa-plus\"></i>', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="btn-group pull-right">
            <button data-toggle="dropdown" class="btn dropdown-toggle">工具 <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu pull-right">
                <li>
                    <?= Html::a('发布信息', ['publish']) ?>
                </li>
            </ul>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'msgtype',
            'key',
            'title',
            'content:ntext',
            // 'description:ntext',
             'url:url',
             'picurl:url',
             'sort',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
