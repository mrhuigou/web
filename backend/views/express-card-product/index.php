<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\ExpressCardProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '发货卡商品管理';
$this->params['breadcrumbs'][] = ['label' => '发货卡管理', 'url' => ['/express-card/index']];
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
				<?= Html::encode($this->title) ?>
            </h3>
			<?= \yii\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

    <p>
        <?= Html::a('创建发货卡商品', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            ['label'=>'绑定提货卡','attribute'=>'express_card_id','value'=>function($model){
                $express_card = \api\models\V1\ExpressCard::findOne(['id'=>$model->express_card_id]);
                return $express_card?$express_card->name:0;
            }],

            ['label'=>'商品','attribute'=>'product_code','value'=>function($model){
                $product = \api\models\V1\Product::findOne(['product_code'=>$model->product_code]);
                if($product){
                    return $product->description->name.'--'.$product->product_code;
                }else{
                    return $model->product_name;
                }

            }],
            ['label'=>'商品所属店铺','attribute'=>'shop_code','value'=>function($model){
                $store = \api\models\V1\Store::findOne(['store_code'=>$model->shop_code]);
                if($store){
                    return $store->name.'--'.$store->store_code;
                }else{
                    return $model->shop_code;
                }

            }],

            ['label'=>'商品数量','attribute'=>'quantity'],

            ['label'=>'备注描述','attribute'=>'description'],
             'status',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update}'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
