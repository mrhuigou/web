<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '消息内容 页面';
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
                控制台 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->

<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'message_content_id',
            'title',
            'device',
            'type',
            'date_added',
            ['class' => 'yii\grid\ActionColumn','template'=>"{update} {delete}"],
        ],
    ]); ?>

</div>
</div>

<?php $this->beginBlock('JS_END') ?>
    $(".push_link").on("click",function(){
        if(confirm('确定要进行推送吗？')){
            $.get("<?=\yii\helpers\Url::to(['page/push'],true)?>",{page_id:$(this).attr("id")},function(data){
                if(data == '推送成功'){
                    alert(data);
                    location.reload();
                }else{
                    alert(推送失败);
                }
            });
        }
    });
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>