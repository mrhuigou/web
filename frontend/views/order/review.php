<?php
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;
use yii\helpers\Url;

$this->title = '商品评论';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div style="min-width:1100px;">
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
                    <div class="user_center clearfix">
                        <div class="whitebg">
                                <table cellpadding="0" cellspacing="0" class="shopcart_list w">
                                    <colgroup>
                                        <col width="30%"/>
                                        <col width="40%"/>
                                        <col width="30%"/>
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th >商品</th>
                                        <th class="tl">评论内容</th>
                                        <th class="tl">评分</th>
                                    </tr>
                                    </thead>
                                </table>
                                    <?php
                                    ListView::begin([
                                        'dataProvider' => $dataProvider,
                                        'itemView' => '_listview_review',
                                        'layout'=>'{items}<div class="tc m10">{pager}</div>',
                                        'itemOptions' => ['class' => 'p10 bdb bdl bdr'],
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
    </div>
<?php $this->beginBlock("JS_Block")?>
    $('.star').raty({
    readOnly   : true,
    width:'200px',
    score: function() {
    return $(this).attr('data-rating');
    }
    });
<?php $this->endBlock()?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJsFile("/assets/script/jquery.raty.js",['depends'=>[\frontend\assets\AppAsset::className()]]);
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);