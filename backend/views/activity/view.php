<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\WeixinMessage */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '活动', 'url' => ['index']];
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
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_6_1" data-toggle="tab">活动详情</a>
            </li>
            <li class="">
                <a href="#tab_6_2" data-toggle="tab">用户列表</a>
            </li>
        </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_6_1">
            <div class="table-toolbar">
                <div class="btn-group">
                    <?= Html::a('返回', "javascript:history.go(-1);", ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                     'description:ntext',
                    'image:url',
                    'address',
                    'signup_end',
                    'begin_datetime',
                    'end_datetime',
                ],
            ]) ?>
        </div>

        <div class="tab-pane fade" id="tab_6_2">
            <div class="users-view">
                <?php if(!is_null($users)){?>
                    <?= GridView::widget([
                        'dataProvider' => $users,
                        'columns' => [
                            'activity_items_id',
                            'order_id',
                            'customer_id',
                            'username',
                            'telephone',
                            'quantity',
                            'total',
                            'remark',
                            'creat_at',
                            ['attribute'=>'状态','value'=>function($model)
                            {
                                $status = ['失败','报名成功'];
                                return $status[$model->status];
                            }],
                        ],
                    ]); ?>
                <?php }else{
                    echo '暂无用户信息';
                }?>
            </div>
        </div>

    </div>
</div>
<?php $this->beginBlock("JS_Block")?>
    $(document).ready(function(){
        $(".pagination a").each(function(){
            this.href = this.href+"#tab_6_2";
        });
    });

<?php $this->endBlock()?>

<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);?>