<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
$this->title = $name;
?>
<div class="page-content">
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12 page-500">
            <div class=" details">
                <h3><?= Html::encode($this->title) ?></h3>
                <p>
                    <?= nl2br(Html::encode($message)) ?>
                </p>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
<!-- BEGIN CONTENT -->
