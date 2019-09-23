<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\widgets\fileapi\Widget as FileAPI;
/* @var $this yii\web\View */
/* @var $model api\models\V1\CategoryDisplayToBrand */
/* @var $form yii\widgets\ActiveForm */
$brand_datas=\api\models\V1\Manufacturer::find()->select('manufacturer_id,code,name')->where(['status'=>1])->all();
$source=[];
if($brand_datas){
    foreach($brand_datas as $value){
        $source[]=[
            'value'=>$value->manufacturer_id,
            'label'=>$value->code.'-'.$value->name,
        ];
    }
}

?>

<div class="category-display-to-brand-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_display_id')->dropDownList(\yii\helpers\ArrayHelper::map(\api\models\V1\CategoryDisplay::find()->where(['parent_id'=>[501,790]])->all(),'category_display_id','description.name')) ?>
    <?= $form->field($model, 'brand_id')->widget(\yii\jui\AutoComplete::className(), [
        'options' => ['class' => 'form-control'],
        'clientOptions' => [
            'source' => $source,
        ],
    ]) ?>
    <?= $form->field($model, 'image')->widget(
        FileAPI::className(),
        [
            'settings' => [
                'url' => ['/page/fileapi-upload']
            ]
        ]
    ) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
