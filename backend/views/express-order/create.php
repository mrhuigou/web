<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\V1\ExpressOrder */

$this->title = 'Create Express Order';
$this->params['breadcrumbs'][] = ['label' => 'Express Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="express-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
