<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '推广码管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
	<!-- BEGIN PAGE HEADER-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<h3 class="page-title">
				推广码 <small>监控、统计、分析</small>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<div class="affiliate-index">
        <div class="row">
            <div class="col-md-12">
                <div class="note note-info">
                    <h4 class="block">专属链接为：</h4>
                    <p>https://m.365jiarun.com/site/index?sourcefrom=<?=Yii::$app->user->identity->code?></p>
                    <p>若在微信内置浏览器中添加后缀 ?sourcefrom=<?=Yii::$app->user->identity->code?> </p>
                    <p>若在App嵌套使用时，请在内置浏览器中设置UserAgent中包含 <?=Yii::$app->user->identity->code?> </p>
                    <p>此链接可以嵌套在APP应用，微信公众号，微博等第三方应用中。</p>
                </div>

            </div>
        </div>
	</div>
</div>