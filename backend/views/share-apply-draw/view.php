<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

use \common\component\image\Image;
use \common\component\Helper\Datetime;


/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '详情';
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
				<?= Html::encode($this->title) ?> <small>监控、统计、分析</small>
			</h3>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_6_1" data-toggle="tab">总览</a>
            </li>
            <li class="">
                <a href="#tab_6_2" data-toggle="tab">收益</a>
            </li>
            <li class="">
                <a href="#tab_6_3" data-toggle="tab">粉丝</a>
            </li>
            <li class="">
                <a href="#tab_6_4" data-toggle="tab">分享订单</a>
            </li>
            <!-- <li class="">
                <a href="#tab_6_2" data-toggle="tab">用户列表</a>
            </li> -->
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_6_1">
                <div class="page-index">
                    <?= \yii\widgets\DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'code',
                            'customer_id',
                            'amount',
                            'status',
                            'created_at:datetime',
                            'update_at:datetime',
                            'trade_no',
                            'remark',
                        ],
                    ]) ?>
                </div>
            </div>
                <div class="tab-pane" id="tab_6_2">
                    <table class="table table-striped table-bordered"><thead>
                        <tr><th>当天收入（元）</th><th>当周收入（元）</th><th>当月收入（元）</th><th>累计收入（元）</th></tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?=$data['cc_today_total'] ? $data['cc_today_total'] : 0;?></td>
                            <td><?=$data['cc_week_total'] ? $data['cc_week_total'] : 0;?></td>
                            <td><?=$data['cc_month_total'] ? $data['cc_month_total'] : 0;?></td>
                            <td><?=$data['cc_history_total'] ? $data['cc_history_total'] : 0;?></td></tr>
                        </tbody>
                    </table>


                </div>

                <div class="tab-pane" id="tab_6_3">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr><th>当天引入</th><th>当周引入</th><th>当月引入</th><th>累计引入</th></tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?=$data['cf_today_total'] ? $data['cf_today_total']:0;?></td>
                            <td><?=$data['cf_week_total'] ? $data['cf_week_total']:0;?></td>
                            <td><?=$data['cf_month_total'] ? $data['cf_month_total']:0;?></td>
                            <td><?=$data['cf_history_total'] ? $data['cf_history_total']:0;?></td>
                        </tr>
                        </tbody>
                    </table>
                    <?php if($followers_datas){?>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr><th>头像</th><th>昵称</th><th>引入时间</th><th>操作</th></tr>
                            </thead>
                            <?php foreach ($followers_datas as $followers_data){?>
                                <tr>
                                    <td><img src="<?=Image::resize($followers_data->follower->photo,100,100)?>" width="40" height="40" alt="ava" class="pop-show"></td>
                                    <td><?=$followers_data->follower->nickname?$followers_data->follower->nickname:"陌生人"?></td>
                                    <td><?=Datetime::getTimeAgo(date('Y-m-d H:i:s',$followers_data->creat_at))?></td>
                                    <td><a href="<?php echo \yii\helpers\Url::to(['customer/view','id'=>$followers_data->customer_id])?>">[查看]</a></td>
                                </tr>
                            <?php }?>
                        </table>
                    <?php }?>

                </div>
                <div class="tab-pane" id="tab_6_4">
                    <?php if($orders){?>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr><th>订单ID</th><th>客户电话</th><th>订单金额</th><th>订单状态</th><th>下单时间</th><th>操作</th></tr>
                            </thead>
                            <?php foreach ($orders as $order){?>
                                <tr>
                                    <td><?php echo $order->order_id?></td>
                                    <td><?=$order->telephone?$order->telephone:"暂无"?></td>
                                    <td><?=$order->total?></td>
                                    <td><?=$order->orderStatus->name?></td>
                                    <td><?=$order->date_added?></td>
                                    <td><a href="<?php echo \yii\helpers\Url::to(['order/view','id'=>$order->order_id])?>">[查看]</a></td>
                                </tr>
                            <?php }?>
                        </table>
                    <?php }?>
                </div>
            </div>
        </div>


</div>
