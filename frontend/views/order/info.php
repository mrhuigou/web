<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
$this->title = '订单详情';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = ['label' => '我的订单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class=" pb10" style="min-width:1100px;">
    <div class="w1100 bc ">
        <!--面包屑导航-->
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'tag' => 'p',
            'options' => ['class' => 'gray6 pb5 pt5'],
            'itemTemplate' => '<a class="f14">{link}</a> > ',
            'activeItemTemplate' => '<a class="f14">{link}</a>',
        ]) ?>
        <div class="bc  clearfix simsun">
            <div class="fl w100 mr10 menu-tree">
                <?= frontend\widgets\UserSiderbar::widget() ?>
            </div>
            <div class="fl w990 ">
                <div class="whitebg">
                    <h2 class="titStyle3 f14 green graybg2">订单编号：<span
                            class="pr30"><?php echo $model->order_no; ?></span>
                        状态： <span class="org pr30"><?= $model->orderStatus->name; ?></span>
                        下单时间：<?php echo date('Y-m-d H:i:s', strtotime($model->date_added)); ?> </h2>

                    <div class="p10">
                        <table cellpadding="0" cellspacing="0" class="order_total bd w">
                            <colgroup>
                                <col width="60%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="20%"/>
                            </colgroup>
                            <tr class="graybg">
                                <th>商品</th>
                                <th>单价(元)</th>
                                <th>数量</th>
                                <th>商品总计</th>
                            </tr>
                        </table>
                        <table class="bdt bdl bdr w mt10 ">
                            <colgroup>
                                <col width="60%"/>
                                <col width="10%"/>
                                <col width="10%"/>
                                <col width="20%"/>
                            </colgroup>
                            <?php if ($model->orderProducts) { ?>
                                <?php foreach ($model->orderProducts as $op) { ?>
                                    <tr>
                                        <td class="bdl bdb tl">
                                            <div class="p5 fl">
                                                <a href="<?= \yii\helpers\Url::to(['product/index', 'shop_code' => $model->store->store_code, 'product_base_code' => $op->product_base_code], true) ?>">
                                                    <img
                                                        src="<?= \common\component\image\Image::resize($op->product->image, 45, 55); ?>"
                                                        width="45" height="55"/>
                                                </a>
                                            </div>
                                            <div class="p5">
                                                <h2>
                                                    <a href="<?= \yii\helpers\Url::to(['product/index', 'shop_code' => $model->store->store_code, 'product_base_code' => $op->product_base_code], true) ?>">
                                                        <?= $op->name; ?></a></h2>

                                                <p class="gray9">
                                                    规格：<?php echo $op['unit'] . ($op['format'] ? ('/' . $op['format']) : ''); ?></p>

                                                <p><?= $op->sku_name ?></p>
                                                <?= frontend\widgets\Order\Promotion::widget(['product' => $op]) ?>
                                            </div>
                                        </td>
                                        <td class="bdl bdb tc">
                                            <?php echo number_format($op['price'], 2); ?>
                                        </td>
                                        <td class="bdl bdb tc"> <?php echo $op['quantity']; ?></td>
                                        <td class="bdl bdb tc"><?php echo number_format($op['total'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($model->orderDigitalProducts) { ?>
                                <?php foreach ($model->orderDigitalProducts as $op) { ?>
                                    <tr>
                                        <td class="bdl bdb tl">
                                            <div class="p5 fl">
                                            </div>
                                            <div class="p5">
                                                <h2><?= $op->model; ?></h2>

                                                <p class="gray9"><?= $op->name; ?> </p>
                                                <?php if ($op->type == 'telephone') { ?>
                                                    <p class="gray9"><?= $op->account; ?> </p>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="bdl bdb  tc">
                                            <?php echo number_format($op['price'], 2); ?>
                                        </td>
                                        <td class="bdl bdb tc"> <?php echo $op['qty']; ?></td>
                                        <td class="bdl  bdb tc">
                                            <p class="fb"><?php echo number_format($op['total'], 2); ?></p>
												<span class="<?php echo ($op['status'] == 1) ? 'green' : 'org'; ?>">
												<?php echo ($op['status'] == 1) ? '已充值' : '未充值'; ?>
												</span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            <tr>
                                <td colspan="4">
                                    <?= frontend\widgets\Order\OrderPromotion::widget(['order' => $model]) ?>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <?php if ($model->orderTotals) { ?>
                        <div class="clearfix tr graybg p5">
                            <?php foreach ($model->orderTotals as $total) { ?>
                                <p class="lh150 f14 pr10"><span class=" pr10"><?= $total->title ?>:</span><span
                                        class="org"><?= $total->value ?></span></p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="p10 ">
                        <?php if (isset($model->orderShipping) && !empty($model->orderShipping)) { ?>
                            <p class="p5"><span
                                    class="fb">收货信息：</span><?= $model->orderShipping->shipping_zone . $model->orderShipping->shipping_city . $model->orderShipping->shipping_district . $model->orderShipping->shipping_address_1; ?>
                                ，<?= $model->orderShipping->shipping_firstname; ?>
                                ，<?= $model->orderShipping->shipping_telephone; ?></p>
                            <div class="line_dash"></div>
                            <p class="p5"><span class="fb">送货时间：</span>
                                <?= $model->orderShipping->delivery ?>  <?= $model->orderShipping->delivery_time ?>
                            </p>
                            <div class="line_dash"></div>
                        <?php } ?>

                        <p class="pt10 pb10"><span class="fb">发票信息：</span>
                            <?= $model->invoice_temp; ?> <?php if (!empty($model->invoice_title)) {
                                echo ":" . $model->invoice_title;
                            } ?> <br>
                        </p>

                        <div class="line_dash"></div>

                        <?php if ($model->orderPayments) { ?>
                            <p class="pt10 pb10"><span class="fb">支付方式：</span>
							<span class="fb">
							<?php foreach ($model->orderPayments as $key => $pay) {
                                echo (($key > 0) ? '，' : '') . $pay['payment_method'] . ($pay['total'] > 0 ? ('(' . number_format($pay['total'], 2) . ')') : '');
                            } ?>
							</span>
                            </p>
                            <div class="line_dash"></div>
                        <?php } ?>
                        <p class="pt10 pb10"><span class="fb">补充说明：</span>
                            <?= $model->comment ? $model->comment : '无' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>