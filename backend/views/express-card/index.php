<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\ExpressCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '提货卡管理';
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
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('创建提货卡', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',

            ['label'=>'法人姓名','attribute'=>'legal_person_name','value'=>function($model){
                $legal_person = \common\models\LegalPerson::findOne(['legal_person_id'=>$model->legal_person_id]);
                return $legal_person->name;
            }],
            'code',
            'name',
            'remark:ntext',

            ['attribute'=>'begin_datetime','filter'=>false] ,
            ['attribute'=>'end_datetime','filter'=>false] ,
             ['label'=>'状态','attribute'=>'status','value'=>function($model){
                if($model->status == 1){
                    return '启用';
                }else{
                    return '停用';
                }
             }],
            ['label'=>'导入','format'=>'raw','value'=>function($model){
                return '['.Html::a('导入卡号密码',['express-card/import-view','express_card_id'=>$model->id]).']';
            }],
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update}{view}{products}',
                'buttons' => [
                        'update'=> function($url, $model, $key){
                            return
                                Html::a('<span class="">[编辑]</span>', $url, ['title' => '审核'] ) ;
                        },

                    'view' => function ($url, $model, $key) {
                        $url = \yii\helpers\Url::to(['/express-card-view/index','ExpressCardViewSearch[express_card_id]'=>$model->id]);
                        return
                            Html::a('<span class="">[卡信息]</span>', $url, ['title' => '审核'] ) ;
                    },
                    'products' =>  function ($url, $model, $key) {
                        $url = \yii\helpers\Url::to(['/express-card-product/index','ExpressCardProductSearch[express_card_id]'=>$model->id]);
                        return
                            Html::a('<span class="">[商品信息]</span>', $url, ['title' => '审核'] ) ;
                    },
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
