<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '佣金提现';
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
				<?= Html::encode($this->title) ?> <small>监控、统计、分析</small>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->

	<div class="page-index">

		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
				'customer_id',
				['label' => '头像',
					'format' => ['image',['width'=>'50', 'height'=>'50']],
					'value' => function ($model) {
						return \common\component\image\Image::resize($model->customer->photo,50,50);
					}
				],
				'customer.telephone',
				'code',
				'amount',
				'created_at:datetime',
				['attribute'=>'status','value'=>function($model){
		            if($model->status){
			            return  Html::tag('span','已对账',[
				            'class' => 'btn btn-info'
			            ]);
                    }else{
			            return  Html::a('未对账','javascript:;',[
				            'class' => 'btn btn-danger commission-draw-btn',
				            'data' => [
					            'id' =>$model->id,
				            ],
			            ]);
                    }
				}, 'format'=>'raw'],
				['class' => 'yii\grid\ActionColumn','template'=>"{view}"],
			],
		]); ?>

	</div>
</div>
<?php $this->beginBlock('JS_END') ?>
$(".commission-draw-btn").on("click",function(){
var obj=$(this).parent("td");
var draw_id=$(this).data("id");
if(confirm("确定要对此记录操作吗？")){
$.post("<?=\yii\helpers\Url::to(['/share-apply-draw/draw'],true)?>",{id:draw_id},function(res){
if(res.status){
obj.html("<span class=\"btn btn-info\">已对账</span> ");
alert(res.message);
}else{
alert(res.message);
}
},'json');
}
});
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
