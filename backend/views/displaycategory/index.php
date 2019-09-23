<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "显示分类";
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
                <?= Html::encode($this->title) ?> <small>查看、增加、更新、删除</small>
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
						显示分类排序、移动：
					</span>
					 您可以对分类条进行拖动进行排序与移动
				</span>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="nestable_list_menu" class="margin-bottom-10">
                <button data-action="expand-all" class="btn blue" type="button">展开</button>
                <button data-action="collapse-all" class="btn" type="button">收起</button>
                <button   class="btn btn-success" type="button" id="saveBtn">保存</button>
                <a href="<?=\yii\helpers\Url::to(['/displaycategory/create'])?>" class="btn btn-success">创建分类</a>
                <a href="<?=\yii\helpers\Url::to(['/category-display-to-brand/index'])?>" class="btn btn-success">分类关联品牌</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <textarea hidden="hidden" id="cate_output"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet green box">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>显示分类结构操作
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
    var UINestable = function () {
    var updateOutput = function (e) {
    var list = e.length ? e : $(e.target),
    output = list.data('output');
    if (window.JSON) {
    output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
    } else {
    output.val('JSON browser support required for this demo.');
    }
    };
    return {
    //main function to initiate the module
    init: function () {

    // activate Nestable for list 1
    $('#w1').nestable({
    group: 1
    })
    .on('change', updateOutput);
    // output initial serialised data
    updateOutput($('#w1').data('output', $('#cate_output')));

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
    $(".fa-trash-o").on("click",function(e){
    var a=$(this).closest('li');
    bootbox.confirm("你真的打算删除吗?", function(result) {
    if(result){
    $.get('/displaycategory/del','cate_id='+a.attr('data-id'));
    }
    });
    });
    $(".reset").on("click",function(e){
    var a=$(this).closest('li');
    $.get('/displaycategory/reset','cate_id='+a.attr('data-id'));
    });
    $("#saveBtn").on('click',function(e){
    $("#saveBtn").text("正在提交...").attr({"disabled":"disabled"});

    var recommend = '';
    $("input[name='recommend']:checked").each(function (i, n) {
    //recommend['info'].push(n.value);

    recommend = recommend+n.value+',';
    });

    $.post('/displaycategory/save','cate_output='+$('#cate_output').val()+'&recommend='+recommend+'&_csrf='+$("meta[name=csrf-token]").attr("content"),function(result){
    $("#saveBtn").text("保存成功");
    });
    });

    }
    };

    }();
    UINestable.init();
<?php $this->endBlock();?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>