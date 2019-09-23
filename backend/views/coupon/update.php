<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model api\models\V1\ClubTry */

$this->title = '编辑折扣券';
$this->params['breadcrumbs'][] = ['label' => '折扣券', 'url' => ['index']];
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
            折扣券 <small>监控、统计、分析</small>
        </h3>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_6_1" data-toggle="tab">折扣券详情</a>
        </li>
        <li class="">
            <a href="#tab_6_2" data-toggle="tab">商品列表</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_6_1">
<div class="club-try-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>

        <div class="tab-pane" id="tab_6_2">
<div class="club-try-create">

    <h1>有效商品</h1>
    <?php Pjax::begin() ?>
    <form class="form-inline " style="margin: 10px 10px;">
    <?= Html::textInput('product_code',null,['class'=>'form-control']) ?>
    <?= Html::submitButton('添加',['id'=>'search_add','class'=>'btn btn-primary']) ?>
    </form>
    <div class="content-container">
    <?= GridView::widget([
        'dataProvider' => $products,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'product.description.name',
            'product.product_code',
            'status',

            ['class' => 'yii\grid\ActionColumn',
             'template'=>'{delete}',
              'buttons'=>['delete' => function ($url, $model, $key) {
              return  Html::a('删除', ['delete-product', 'id' => $model->coupon_product_id], [
            'class' => 'btn btn-danger del-button',
            'id' => $model->coupon_product_id,
            'data' => [
                'confirm' => '确定要删除吗？',
                'method' => 'post',
            ],
        ]);
          }]
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>
    </div>
</div>
</div>




</div>
</div>
</div>
    <?php $this->beginBlock("JS_Block")?>
        
        $("#search_add").on('click',function(){
            var pd_code = $("input[name='product_code']").val();
            $.post("<?=\yii\helpers\Url::to(['/coupon/add-product'],true) ?>",{product_code:pd_code,id:<?=$model->coupon_id?>},function(data){
                if(data == 'wrong'){
                    alert('参数错误');
                }else{
                    if(location.hash == "#tab_6_2"){
                    location.reload();
                    }else{
                        window.location.href=window.location.href+"#tab_6_2";
                        location.reload();
                    }
                }
            });
        });

    <?php $this->endBlock()?>

    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);