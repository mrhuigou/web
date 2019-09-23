<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "系统分类";
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
                <?= Html::encode($this->title) ?> <small>查看</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <div class="well">
				<span class="label label-danger">
					提示
				</span>
				<span>
					<span class="bold">
						系统分类无法在前台修改：
					</span>
				</span>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="nestable_list_menu" class="margin-bottom-10">
                <button data-action="expand-all" class="btn blue" type="button">展开</button>
                <button data-action="collapse-all" class="btn" type="button">收起</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet green box">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>系统分类结构
                    </div>

                </div>
                <div class="portlet-body">
                    <?=\backend\widgets\nestable\Nestable::widget([
                        'items'=>$data,
                    ])?>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $this->beginBlock('JS_END');?>
$('#nestable_list_menu').on('click', function (e) {
var target = $(e.target),
action = target.data('action');
if (action === 'expand-all') {
$('.dd').nestable('expandAll');
}
if (action === 'collapse-all') {
$('.dd').nestable('collapseAll');
}
});
<?php $this->endBlock();?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>