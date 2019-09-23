<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员中心';
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
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <br>
    <?= GridView::widget([
        'layout'=> "{summary}\n<div class=\"table-responsive\">{items}</div>\n{pager}",
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'customer_id',
	        ['label' => '头像',
		        'format' => ['image',['width'=>'50', 'height'=>'50']],
		        'value' => function ($model) {
			        return \common\component\image\Image::resize($model['photo'],50,50);
		        }
	        ],
	        ['label' => '是否关注',
		        'format'=>'raw',
		        'value' => function ($model) {
                    $auth_model = \api\models\V1\CustomerAuthentication::findOne(['customer_id'=>$model['customer_id'],'provider'=>'WeiXin','status'=>1]);
                    if(is_null($auth_model)){
                        return "<span style='color:red'>否</span>";
                    }else{
                        return "<span style='color:green'>是</span>";
                    }
		        }
	        ],
            'firstname',
            'nickname',
             'email:email',
             'telephone',
             'status',
            ['label'=>'下单总数','value'=>'order_count'],
            ['label'=>'同步订单总数','value'=>'sent_order_count' ],
             'date_added',
             ['attribute'=>'管理','value'=>function($model){
                return  Html::a('登录',\yii\helpers\Url::to(['auth_key','customer_id'=>$model['customer_id']],true));
                }, 'format'=>'raw'],
            ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}',
                'buttons'=>[
                    'view' => function ($url, $model, $key) {
                        return  Html::a('<span class="glyphicon glyphicon-eye-open"> </span>', ['view', 'id' => $model['customer_id']], [
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"> </span>', ['update', 'id' => $model['customer_id']]);
                    }
                ]

            ],
        ],
    ]); ?>
</div>
