<?php
use yii\widgets\Breadcrumbs;
use api\models\V1\Coupon;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Customer */
$this->title = $model->customer_id;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
<?= \backend\widgets\Customizer::widget(); ?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
				<?= Html::encode($this->title) ?>
                <small>监控、统计、分析</small>
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
                <a href="#tab_6_1" data-toggle="tab">用户详情</a>
            </li>
            <li class="">
                <a href="#tab_6_2" data-toggle="tab">交易余额</a>
            </li>
            <li class="">
                <a href="#tab_6_3" data-toggle="tab">优惠券</a>
            </li>
            <li class="">
                <a href="#tab_6_4" data-toggle="tab">授权认证</a>
            </li>
            <li>
                <a href="<?= \yii\helpers\Url::to(['order/index', 'OrderSearch[customer_id]' => $model->customer_id]) ?>">往期订单</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_6_1">
                <div class="order-view">
                    <p>
						<?= Html::a('编辑', ['update', 'id' => $model->customer_id], ['class' => 'btn btn-primary']) ?>
                    </p>

					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'customer_id',
							'firstname',
							'nickname',
							'onmobile',
							'email:email',
							'email_validate:email',
							'telephone',
							'telephone_validate',
							'gender',
							'birthday',
							'education',
							'occupation',
							'idcard',
							'idcard_validate',
							'newsletter',
							'address_id',
							'customer_group_id',
							'ip',
							'status',
							['label' => '头像',
								'format' => ['image', ['width' => '50', 'height' => '50']],
								'value' => \common\component\image\Image::resize($model->photo, 50, 50),
							],
							'signature',
							'source_from',
							'user_agent',
							'date_added',
						],
					]) ?>

                </div>
            </div>

            <div class="tab-pane " id="tab_6_2">
                <div class="trans-view">
                    <h3>余额调整 - 当前余额：<?= $total_tran ?>元</h3>
					<?php $form = ActiveForm::begin(); ?>
                    调整说明：<?php echo Html::Input('text', 'title', null, ['class' => 'form-control', 'id' => 'change_title']); ?>
                    <br>调整金额：<?php echo Html::Input('text', 'total', null, ['class' => 'form-control', 'id' => 'change_value']); ?>
					<?php echo Html::Input('hidden', 'type', 'tran'); ?>
                    <br><?= Html::submitButton('添加记录', ['class' => 'btn btn-primary', 'id' => 'change_submit']) ?>
                    <hr>
					<?php ActiveForm::end(); ?>
					<?php if (!is_null($trans)) { ?>
						<?= GridView::widget([
							'dataProvider' => $trans,
							'columns' => [
								'order_id',
								'description',
								'amount',
								'date_added'
							],
						]); ?>

					<?php } else {
						echo '暂无相关信息';
					} ?>
                </div>
            </div>

            <div class="tab-pane " id="tab_6_3">
                <div class="coupons-view">
                    <h3>添加优惠券</h3>
                    说明：<?php echo Html::Input('text', 'title', null, ['class' => 'form-control', 'id' => 'coupon_title']); ?>
                    检索优惠券码或名称：<?= \yii\jui\AutoComplete::widget([
						'name' => 'search',
						'options' => ['id' => 'search', 'class' => 'form-control'],
						'clientOptions' => [
							'source' => "/customer/coupon-auto-complete"
						],
						'clientEvents' => [
							'select' => 'function(event, ui) {
                                           var html="<li data-content="+ui.item.value+">"+ui.item.label+"<span class=\"del-item\" style=\"float: right;color: red;padding-right: 10px;\">删除</span></li>";
                                          $("#group_value").append(html); 
                                          $("#search").val("");
                                          return false;
                                        }'
						]
					]) ?>
                    优惠券：
                    <ul id="group_value" style="height: 80px;border: 1px solid #eee;overflow-y: scroll;"></ul>
                    <div class="btn-group" style="margin-top: 10px;">
						<?= Html::button('添加记录', ['class' => 'btn btn-primary', 'id' => 'coupon_submit']) ?>
                    </div>
                    <hr>
					<?php if (!is_null($coupons)) { ?>
						<?= GridView::widget([
							'dataProvider' => $coupons,
							'columns' => [
								'customer_coupon_id',
								'coupon.name',
								'description',
								'is_use',
								'start_time',
								'end_time',
								'date_added',
								'date_used',
								['attribute' => '管理', 'value' => function ($data) {
									return Html::a('删除', \yii\helpers\Url::to(['coupon', 'customer_coupon_id' => $data['customer_coupon_id']], true), [
										'class' => 'btn btn-danger',
										'data' => [
											'confirm' => '您确定要删除此记录吗?',
											'method' => 'post',
										],
									]);
								}, 'format' => 'raw'],
							],
						]); ?>

					<?php } else {
						echo '暂无相关信息';
					} ?>

                </div>
            </div>
            <div class="tab-pane " id="tab_6_4">
	            <?= GridView::widget([
		            'dataProvider' =>$auth,
		            'columns' => [
			            'provider',
			            'display_name',
			            ['label' => '头像',
				            'format' => ['image',['width'=>'50', 'height'=>'50']],
				            'value' => function ($auth) {
					            return \common\component\image\Image::resize($auth->photo_url,50,50);
				            }
			            ],
			            'date_added',
                        'date_update',
                        'status'
		            ],
	            ]); ?>
            </div>
        </div>
    </div>
<?php $this->beginBlock("JS_Block") ?>

    $("#change_submit").on("click",function(){
    if(isNaN($("#change_value").val()) == true || $("#change_value").val() == ''){
    alert('调整金额必须为数字');
    return false;
    }
    if($("#change_title").val() == ''){
    alert('调整说明不能为空');
    return false;
    }
    });

    $("#total_submit").on("click",function(){
    if($("#total_title").val() == ''){
    alert('说明不能为空');
    return false;
    }
    });

    $("#chest_submit").on("click",function(){
    if($("#chest_treasureid").val() == ''){
    alert('渠道 ID 不能为空');
    return false;
    }
    if($("#chest_code").val() == ''){
    alert('商品 CODE 不能为空');
    return false;
    }
    });

    $("#check_code").on("click",function(){
    var pcode = $("#chest_code").val();
    $.get("/customer/check-product", { code: pcode },
    function(data){
    $("#result").text(data);
    });
    return false;
    });
    $("#coupon_submit").on("click",function(){
    var des=$("#coupon_title").val();
    var arr=[];
    $("#group_value li").each(function(){
    arr.push($(this).data("content"));
    });
    $.post('<?= \yii\helpers\Url::to(['/customer/add-coupon']) ?>',{'customer_id':'<?= $model->customer_id ?>
    ','description':des,coupons:arr},function(res){
    location.reload(true);
    });
    });
    $("body").on("click",'.del-item',function(){
    $(this).parent("li").remove();
    });
<?php $this->endBlock() ?>

<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'], \yii\web\View::POS_END);
