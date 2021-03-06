<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model api\models\V1\CmsType */

$this->title = '新增地址';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['/customer/index']];
$this->params['breadcrumbs'][] = ['label' => '地址管理', 'url' => ['index','customer_id'=>\Yii::$app->request->get('customer_id')]];
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
            <?=$this->title?> <small>监控、统计、分析</small>
        </h3>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<div class="address-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>