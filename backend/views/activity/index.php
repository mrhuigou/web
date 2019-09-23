<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;



/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\WeixinMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '活动';
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
            'title',
            // 'description:ntext',
            ['attribute'=>'image','value'=>function($data){
                return  Html::img(\common\component\image\Image::resize($data['image'],100,100)); }, 'format'=>'raw'],
            'signup_end',
            'begin_datetime',
            'end_datetime',
            ['attribute'=>'管理','value'=>function($data){
                return  Html::a('管理员',\yii\helpers\Url::to(['administrator','id'=>$data['id']],true)).'<br><br>'.Html::a('成员',\yii\helpers\Url::to(['/activity-user/index','activity_id'=>$data['id']],true)).'<br><br>'.Html::a($data["is_recommend"]==1?'取消推荐':'设为推荐',\yii\helpers\Url::to(['/activity/recommend','id'=>$data['id']],true)); }, 'format'=>'raw'],
            ['class' => 'yii\grid\ActionColumn','template'=>'{update}'],
        ],
    ]); ?>

</div>
