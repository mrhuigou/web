<?php
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;
use yii\grid\GridView;

$this->title = '我的收藏';
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

                    <div class="user_center clearfix whitebg">
                        <div class="">
                            <div class="shopcart_other">
                                <ul class="itagTit clearfix">
                                    <li class="<?php if (\Yii::$app->request->get("type") && \Yii::$app->request->get("type") == "store") {
                                    } else {
                                        echo "current";
                                    } ?>"><a href="<?= \yii\helpers\Url::to(['/collect'], true) ?>">商品收藏</a></li>
                                    <li class="<?php if (\Yii::$app->request->get("type") && \Yii::$app->request->get("type") == "store") {
                                        echo "current";
                                    } ?>"><a href="<?= \yii\helpers\Url::to(['/collect', 'type' => 'store'], true) ?>">店铺收藏</a>
                                    </li>
                                    <!-- -->
                                </ul>
                            </div>
                            <p class="whitebg p15 bdb mb5 none">
                                <span class="icon_light">您的收藏夹中有 <span
                                        class="org"><?php echo isset($total_wish) ? $total_wish : 0; ?></span> 个宝贝！最多能添加50个宝贝！</span>
                            </p>
                            <?php if (\Yii::$app->request->get("type") && \Yii::$app->request->get("type") == "store") { ?>
                                <?php

                                ListView::begin([
                                    'dataProvider' => $dataProvider,
                                    'itemView' => '_list_store',
                                    'layout' => '{items}<div class="p20 tc">{pager}</div>',
                                    'itemOptions' => ['class' => 'mydd_box'],
                                    'pager' => [
                                        'maxButtonCount' => 10,
                                        'nextPageLabel' => Yii::t('app', '下一页'),
                                        'prevPageLabel' => Yii::t('app', '上一页'),
                                    ],
                                    'emptyText' => '提示：您还没有收藏任何店铺!',
                                    'emptyTextOptions' => ['class' => 'tc bd'],

                                ]); ?>
                                <?php ListView::end(); ?>
                            <?php } else { ?>
                                <div class="p10">
                                    <table cellpadding="0" cellspacing="0" class="shopcart_list w bdb">
                                        <tbody>
                                        <?= GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'showHeader' => true,
                                            'tableOptions' => ['class' => 'shopcart_list w bdb'],
                                            'layout' => "{items}\n<div class=\"tc p20\">{pager}</div>",
                                            'emptyText' => '提示：您还没有任何收藏信息!',
                                            'emptyTextOptions' => ['class' => 'tc bd'],
                                            'columns' => [
                                                //显示的字段
                                                //code的值
                                                ['attribute' => '名称',
                                                    'format' => 'raw',
                                                    'value' => function ($model) {
                                                        $name = '<a href="' . \yii\helpers\Url::to(['/product/index', 'shop_code' => $model->productbase->store_code, 'product_base_code' => $model->productbase->product_base_code], true) . '" class="fl"><img src="' . \common\component\image\Image::resize($model->productbase->image, 45, 55) . '" width="45" height="55" class="bd mr10 db show_image"></a>';
                                                        $name .= '<p class="fl clearfix w270 tl"><a href="' . \yii\helpers\Url::to(['/product/index', 'shop_code' => $model->productbase->store_code, 'product_base_code' => $model->productbase->product_base_code], true) . '" class="mb5 dib">' . $model->productbase->description->name . '</a> <br></p>';
                                                        return $name;
                                                    }],
                                                ['attribute' => '所在店铺', 'value' => 'productbase.shop.name'],
                                                ['attribute' => '价格', 'value' => function ($model) {
                                                    return $model->productbase->Price;
                                                }],
                                                ['attribute' => '库存', 'value' => function ($model) {
                                                    if ($model->productbase->stockcount < 1) {
                                                        return '售罄';
                                                    } elseif ($model->productbase->stockcount < 10) {
                                                        return '紧张';
                                                    } else {
                                                        return '充足';
                                                    }
                                                }],
                                                [
                                                    'label' => '更多操作',
                                                    'format' => 'raw',
                                                    'value' => function ($model) {
                                                        $url = '<a href="javascript:void(0);" val="' . $model->customer_collect_id . '" class="removeBtn green">取消收藏</a>';
                                                        return $url;
                                                    }
                                                ]
                                            ],
                                        ]); ?>

                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>

                            <?php if (isset($products) && !empty($products)) { ?>
                                <div class="whitebg p5 mt10 clearfix">
                                    <ul class="pages clearfix fr">
                                        <?php echo $pagination; ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->beginBlock("JS_Block") ?>
    $(".removeBtn").click(function(){
    var id = $(this).attr('val');

    $.ajax({
    url: '<?= \yii\helpers\Url::to(['/collect'], true) ?>',
    type: 'post',
    data: 'id='+id,
    dataType: 'json',
    success: function(json) {
    window.location.reload();
    }
    });
    });

<?php $this->endBlock() ?>

<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_Block'], \yii\web\View::POS_END);