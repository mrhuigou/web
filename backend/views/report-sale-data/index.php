<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '销售数据统计';
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
                销售数据统计<small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <div class="return-base-index">
        <a class="btn btn-lg blue" data-toggle="modal"  href="#add_tag">添加标签</a>
        <div class="modal fade" id="add_tag" tabindex="-1" role="add_tag" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">添加查询标签</h4>
                    </div>
                    <div class="modal-body">
                        <form action="#" class="form-horizontal" id="add_tag_form">
                            <div class="form-group">
                                <label class="control-label col-md-2">标签名称</label>
                                <div class="col-md-10">
                                    <input class="form-control" name="title">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">查询语句</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="content"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a  href="javascript:;" class="btn default" data-dismiss="modal">取消</a>
                        <a class="btn blue" href="javascript:;" id="add_tag_submit">提交</a>
                    </div>
                </div>
            </div>
        </div>
	    <?= GridView::widget([
		    'dataProvider' => $dataProvider,
		    'columns' => [
			    ['class' => 'yii\grid\SerialColumn'],
			    'id',
			    'title',
			    ['class' => 'yii\grid\ActionColumn','template'=>'{view} {update} {execute}' , 'buttons'=>[
				    'execute'=>function($url,$model,$key){
					    return Html::a('<span class="glyphicon">执行查询</span>', \yii\helpers\Url::to(['/report-sale-data/execute','id'=>$model->id]), ['title' => '执行查询'] );
				    }
			    ],
			    ],
		    ],
	    ]); ?>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
$("#add_tag_submit").on('click',function(){
$.post('<?=\yii\helpers\Url::to(['/report-sale-data/create'])?>',$("#add_tag_form").serialize(),function(data){
location.reload();
});
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>

