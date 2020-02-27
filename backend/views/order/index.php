<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '订单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
	<!-- BEGIN STYLE CUSTOMIZER -->
	<?= \backend\widgets\Customizer::widget(); ?>
	<!-- END STYLE CUSTOMIZER -->
	<!-- BEGIN PAGE HEADER-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<h3 class="page-title">
				控制台
				<small>监控、统计、分析</small>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<div class="order-index">
		<?php echo $this->render('_search', ['model' => $searchModel]); ?>
        <br>
        <style>.grid-view{
                clear: both;}</style>
		<?php Pjax::begin(); ?>

			<?= GridView::widget([
				'layout'=> "{summary}\n<div class=\"table-responsive \" style='clear: both'>{items}</div>\n{pager}",
				'dataProvider' => $dataProvider,
				// 'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					'order_id',
                    ['label'=>'退货状态','format'=>'raw','value'=>function($model){
//			            $return = \api\models\V1\ReturnBase::findOne(['order_id'=>$model->order_id]);
//			            if($return){
//                            return '['.Html::a($return->returnStatus->name,['refund/view','id'=>$return->return_id]).']';
//                        }else{
//			                return "无";
//                        }

                         if($return = \api\models\V1\ReturnBase::find()->where(['order_id'=>$model->order_id])->all()){

                            $return_status6 = false;//退货状态 （取消退货）  1.4.6
                            $return_status1 = false;//退货状态 （待处理）
                            $return_id = '';
                            foreach ($return as $key => &$value){
                                //待处理判断
                                if($value->return_status_id == 1){
                                    $return_status1 = true;
                                    $return_id = $value->return_id;
                                    break;
                                }
                                //取消退货处理
                                if($value->return_status_id == 6){
                                    $return_status6 = true;
                                    $return_id6 = $value->return_id;
                                }else{
                                    $return_status6 = false;
                                    $return_id4 = $value->return_id;
                                    break;
                                }
                            }

                             if(!$return_status1) {//不存在待处理
                                 if (!$return_status6 && !empty($return_id4)) {//完成退货
                                     $return_id = $return_id4;
                                 } else {
                                     $return_id = $return_id6;
                                 }
                             }
                            $return_info = \api\models\V1\ReturnBase::findOne(['return_id'=>$return_id]);
                            return '['.Html::a($return_info->returnStatus->name,['refund/view','id'=>$return_id]).']';

                         }else{
                             return "无";
                         }

                    }],
					['attribute'=>'affiliate_id','label'=>'分销商','value'=>function($model){
			            if($model->affiliate_id ==0){
			                return '每日惠购会员';
                        }else{
                            if($model->affiliate){
                                return $model->affiliate->username;
                            }else{
                                return '错误的数据';
                            }
                        }
                    }],
					'order_type_code',
					'order_no',
					'firstname',
					'telephone',
					'orderShipping.shipping_firstname',
					'orderShipping.shipping_telephone',
					'payment_method',
					'total',
					'orderStatus.name',
					'date_added',
					'sent_to_erp',
                    'invoice_temp',
                    ['label'=>'下单总数','value'=>function($model){
			            $order_count = \api\models\V1\Order::find()->where(['customer_id'=>$model->customer_id])->count();
			            return $order_count;
                    }],
                    ['label'=>'同步订单总数','value'=>function($model){
                        $order_count = \api\models\V1\Order::find()->where(['customer_id'=>$model->customer_id,'sent_to_erp'=>'Y'])->count();
                        return $order_count;

                    }],
					['class' => 'yii\grid\ActionColumn', 'template' => '{view}',
                        'buttons'=>[
                            'view'=>function($url,$model,$key){
			                    if($model->order_type_code !='ACTIVITY'){
			                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',$url,['title'=>'查看','data-pjax'=>0]);
                                }
                            }
                        ]
                        ],
				],
			]); ?>
		<?php Pjax::end(); ?>
	</div>
</div>