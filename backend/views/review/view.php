<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;
$this->title = '评论回复: ' . ' ' . $model->review_id;
$this->params['breadcrumbs'][] = ['label' => '评论管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->review_id, 'url' => ['view', 'id' => $model->review_id]];
$this->params['breadcrumbs'][] = '回复';


/* @var $this yii\web\View */
/* @var $model api\models\V1\ReturnBase */
/* @var $form yii\widgets\ActiveForm */
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
                订单管理 <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <?php
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'product.description.name',
            'author',
            'text',
            'rating',
            'service',
            'delivery',
            'status'
        ],
    ]);
    ?>
<?php if(!empty($model_replay)){?>

    <table class="table table-striped table-bordered detail-view" style="width:85%;border:1px dotted rgb(204, 204, 204);">
        <tbody><tr>
            <td>回复评论</td>
            <td></td>
        </tr>
        <tr>
            <td><span class="required">*</span> 回复内容</td>
            <td>
                <?= $model_replay->text;?>
            </td>
        </tr>


        <tr>
            <td>回复时间</td>
            <td>
                <?php echo $model_replay->date_added;?>
            </td>
        </tr>
        <tr>
            <td>最后一次修改时间</td>
            <td>
                <?php echo $model_replay->date_modified;?>
            </td>
        </tr>
        <tr>
            <td>状态</td>
            <td>
                <?php if($model_replay->status == 1){ echo "启用";}else{ echo "停用";};?>
            </td>
        </tr>
        </tbody></table>
<?php }else{ //没有任何回复?>

    <?php }?>
</div>