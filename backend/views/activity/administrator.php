<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model api\models\V1\ClubTry */

$this->title = '活动管理员';
$this->params['breadcrumbs'][] = ['label' => '活动', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = '编辑';
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
            活动管理员 <small>监控、统计、分析</small>
        </h3>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->

<div class="activity-administrator">
    <h1><?=$activity->title?></h1>
    <form class="form-inline">
    <?=Html::input('text','search-input',null,['id'=>'search-input','class'=>'form-control']);?>
    <?=Html::input('button','search-btn','搜索',['id'=>'search-btn','class'=>'btn btn-primary']);?>
    </form>
    <br>
    <div id="well-box"></div>
    <?= GridView::widget([
        'dataProvider' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'customer_id',
            'customer.nickname',
            'customer.telephone',
            'customer.email',
            ['class' => 'yii\grid\ActionColumn',
             'template'=>'{delete}',
              'buttons'=>['delete' => function ($url, $model, $key) {
              return  Html::a('删除', ['remove-administrator', 'id' => $model->activity_id,'customer_id'=>$model->customer_id], [
            'class' => 'btn btn-danger del-button',
            'data' => [
                'confirm' => '确定要删除吗？',
                'method' => 'post',
            ],
        ]);
          }]
            ],
        ],
    ]); ?>

</div>

    <?php $this->beginBlock("JS_Block")?>
        
        $("#search-btn").on('click',function(){
            var pd_code = $("#search-input").val();
            $.post("<?=\yii\helpers\Url::to(['search-customer'],true) ?>",{keyword:pd_code,id:<?=$activity->id?>},function(data){
                if(data == 'wrong'){
                    alert('参数错误');
                }else{
                    $("#well-box").html(data);
                }
            });
        });

    <?php $this->endBlock()?>

    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);