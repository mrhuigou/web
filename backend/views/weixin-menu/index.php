<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use \Yii;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "微信自定义菜单";
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
                <?= Html::encode($this->title) ?> <small>菜单设置</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <?php if(Yii::$app->getSession()->hasFlash('success')):?>
        <div class="alert alert-success">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
            <?php echo Yii::$app->getSession()->getFlash('success')?>
        </div>
    <?php endif?>
    <?php if(Yii::$app->getSession()->hasFlash('error')):?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
            <?php echo Yii::$app->getSession()->getFlash('error')?>
        </div>
    <?php endif?>
    <div class="row">
        <div class="col-md-12">
            <div class="note note-info">
                <h4 class="block">配置注意事项</h4>
                <p>
                    1、自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。<br/>
                    2、一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。<br/>
                    3、创建自定义菜单后，由于微信客户端缓存，需要24小时微信客户端才会展现出来。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。
                </p>
            </div>

        </div>
    </div>
    <div class="table-toolbar">
        <div class="btn-group">
            <?= Html::a('返回', "javascript:history.go(-1);", ['class' => 'btn btn-primary']) ?>
            <?= Html::a('创建菜单 <i class=\"fa fa-plus\"></i>', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="btn-group pull-right">
            <button data-toggle="dropdown" class="btn dropdown-toggle">工具 <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu pull-right">
                <li>
                    <?= Html::a('发布到微信', ['publish']) ?>
                </li>
            </ul>
        </div>
    </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'type',
                'name',
                'url:url',
                'key',
                 'pid',
                 'status',
                 'sort',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

</div>