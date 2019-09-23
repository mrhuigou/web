<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "节点管理";
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
                <?= Html::encode($this->title) ?> <small>节点查看、增加、更新、删除</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <div class="btn-group">
        <?= Html::a('返回', "javascript:history.go(-1);", ['class' => 'btn btn-primary']) ?>
        <?= Html::a('创建节点', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'name','value'=>function($data){
                $url = \yii\helpers\Url::to(['/node/index','pid'=>$data['id']]);
                return  Html::a($data['name'], $url); }, 'format'=>'raw'],
            ['attribute'=>'title_prefix', 'format'=>'raw'],
            ['attribute'=>'title', 'format'=>'raw'],
            'link',
            'status',
             'remark',
             'sort',
             'pid',
             'level',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
