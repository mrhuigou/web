<?php
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;

$this->title = '我的优惠券';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="" style="min-width:1100px;">
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
                <div class="whitebg p5 mb10 ">
                    <a class="fr" href="<?=\yii\helpers\Url::to(['/coupon/index'])?>">
                        <i class="iconfont f25 red">&#xe635;</i>
                        <span class="vm2">领取更多优惠券</span>
                    </a>
                    <div class="orderCata clearfix">
                        <a href="<?= \yii\helpers\Url::to(['/user-coupon/index'], true); ?>" class="cur">我的优惠券</a>
                        <a href="<?= \yii\helpers\Url::to(['/user-coupon/card'], true); ?>" >优惠券充值</a>
                    </div>
                </div>
                <div class="whitebg">
                    <!--列表-->
                    <div class="clearfix mt5">
                        <?php
                        ListView::begin([
                            'dataProvider'=>$dataProvider,
                            'itemView'=>"_listview",
                            'options'=>['class' => 'list-view clearfix'],
                            'layout'=>'{items}</div><div class="tc m10">{pager}',
                            'itemOptions'=>['class'=>'pw33 fl'],
                            'pager'=>[
                                'maxButtonCount'=>10,
                                'nextPageLabel'=>Yii::t('app','下一页'),
                                'prevPageLabel'=>Yii::t('app','上一页'),
                            ],
                            'emptyText'=>'提示：您还没有任何订单信息!',
                            'emptyTextOptions'=>['class' => 'tc bd'],
                        ]);?>
                        <?php ListView::end();?>
                    </div>

                    <!--规则-->
                    <div class="yellowBox p15 lh200">
                        <h3 class="f14 fb">特别提示：</h3>
                        <div class="line_dash mt5 mb5"></div>
                        <p>（1）当您从购物车中去结算时，在订单确认页面可以选择（或输入）您的优惠券号，获得相应的优惠。</p>
                        <p>（2）每个订单限使用一张优惠券，优惠券限仅使用一次。</p>
                        <p>（3）优惠券有不同的类型，如仅限某品牌、某品类使用的优惠券等。</p>
                        <p>（4）请注意：优惠券是有过期时间的哦！请在过期之前使用。</p>
                        <p>（5）若您获得满额返券的订单发生退货，因该订单所返的所有优惠券都将被取消。</p>
                        <p>（6）当您使用优惠券购买的商品发生退货时，将不会退还该优惠券分摊优惠至每个商品中的金额。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
