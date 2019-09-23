<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Node */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="node-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title_prefix')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sort')->textInput() ?>
    <?php
    $data=\api\models\V1\Node::find()->asArray()->all();
    if($model->pid){
        $tree=[
            ['id'=>'0','pid'=>'-1','text'=>'应用顶级','state'=>['opened'=>true]],
        ];
    }else{
        $tree=[
            ['id'=>'0','pid'=>'-1','text'=>'应用顶级','state'=>['opened'=>true,'selected'=>true]],
        ];
    }
    foreach($data as $value){
        if($model->pid==$value['id']){
            $tree[]=[
                'id'=>$value['id'],
                'pid'=>$value['pid'],
                'text'=>$value['title'],
                "state"=>['selected'=>true]
            ];
        }else{
            $tree[]=[
                'id'=>$value['id'],
                'pid'=>$value['pid'],
                'text'=>$value['title'],
            ];
        }
    }
    $data=\common\component\Helper\Helper::genTree($tree);
    ?>
    <?= $form->field($model, 'pid')->widget(\talma\widgets\JsTree::className(),[
        'model' => $model,
        'core' => [
            'data'=>$data,
        ],
        "plugins"=> ["state"]
    ])
    ?>

    <?php $model->status=$model->status!==0?1:0?>
    <?= $form->field($model, 'status')->radioList([0=>"关闭",1=>"启用"]) ?>
    <?= $form->field($model, 'remark')->textarea(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
