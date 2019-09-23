<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Page */

$this->title = '编辑页面: ' ;
$this->params['breadcrumbs'][] = ['label' => 'CMS 页面', 'url' => ['index']];
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
            <?= Html::encode($this->title) ?><small>监控、统计、分析</small>
        </h3>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<div class="page-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>