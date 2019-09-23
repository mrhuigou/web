<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Menu;
use yii\helpers\Json;
/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */
/* @var $form yii\widgets\ActiveForm */
$menu_source=[];
$route_source=[];
if($menu=Menu::getMenuSource()){
    foreach($menu as $value){
        $menu_source[]=[
            'value'=>$value['name'],
            'label'=>$value['name'],
        ];
        $route_source[]=[
            'value'=>$value['route'],
            'label'=>$value['route'],
        ];
    }
}

?>
<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>

            <?= $form->field($model, 'parent_name')->widget(\yii\jui\AutoComplete::className(), [
                'options' => ['class' => 'form-control'],
                'clientOptions' => [
                    'source' => $menu_source,
                ],
            ])?>

            <?= $form->field($model, 'route')->widget(\yii\jui\AutoComplete::className(), [
                'options' => ['class' => 'form-control'],
                'clientOptions' => [
                    'source' => $route_source,
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'icon')->textInput() ?>
            <?= $form->field($model, 'order')->input('number') ?>

            <?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="form-group">
        <?=
        Html::submitButton($model->isNewRecord ?  'Create' :  'Update', ['class' => $model->isNewRecord
                    ? 'btn btn-success' : 'btn btn-primary'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
