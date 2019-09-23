<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\ExpressCardViewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '发货卡明细管理';
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
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('生成卡信息', ['generate'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('创建卡明细', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['label'=>'所属法人','value'=>function($model){
                $express_card = \api\models\V1\ExpressCard::findOne(['id'=>$model->express_card_id]);
                return $express_card->legalPerson->name;
            }],
            ['label'=>'关联提货卡','attribute'=>'express_card_id','value'=>function($model){
                $express_card = \api\models\V1\ExpressCard::findOne(['id'=>$model->express_card_id]);
                return $express_card->name;
            }],

            ['label'=>'卡号','attribute'=>'card_no'],
            ['label'=>'卡密','attribute'=>'card_pwd'],
            ['label'=>'状态','attribute'=>'status','value'=>function($model){
                if($model->status == 1){
                    return '已激活';
                }else{
                    return '未激活';
                }

            }],
            // 'version',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update}'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
