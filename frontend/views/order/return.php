<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

$this->title = '退货单';
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
                <div class="whitebg">
                    <table cellpadding="0" cellspacing="0" class="order_total  w">
                        <colgroup>
                            <col width="50%"/>
                            <col width="10%"/>
                            <col width="10%"/>
                            <col width="15%"/>
                            <col width="15%"/>
                        </colgroup>
                        <tr class="graybg">
                            <th>退货商品</th>
                            <th>数量</th>
                            <th>商品金额</th>
                            <th>退货金额</th>
                            <th>订单状态</th>
                        </tr>
                    </table>
                </div>

                <?php
                ListView::begin([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_listview_return',
                    'layout' => '{items}<div class="m10 tc">{pager}</div>',
                    'itemOptions' => ['class' => 'mydd_box'],
                    'pager' => [
                        'maxButtonCount' => 10,
                        'nextPageLabel' => Yii::t('app', '下一页'),
                        'prevPageLabel' => Yii::t('app', '上一页'),
                    ],
                    'emptyText' => '提示：您还没有任何订单信息!',
                    'emptyTextOptions' => ['class' => 'tc bd'],
                ]); ?>

                <?php ListView::end(); ?>

            </div>
        </div>
    </div>
</div>
</div>