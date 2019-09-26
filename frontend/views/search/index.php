<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
use yii\widgets\LinkPager;
use yii\helpers\Url;
$this->title ='商品分类'.'- 每日惠购（mrhuigou.com）- 青岛首选综合性同城网购-发现达人体验-分享同城生活';
?>
<div class="content" id="content">
    <div class="layout grid-m0 J_TLayout">
        <div class="col-main">
            <div class="main-wrap">
<div class="J_TModule" data-title="搜索列表">
<div class="skin-box tb-module tshop-pbsm tshop-pbsm-tmall-srch-list" id="TmshopSrchNav">
<div class="skin-box-bd">
    <?=
    \yii\widgets\Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options'=>['class'=>'breadcrumb crumbSlide-con clearfix']
    ]) ?>
        <div class="crumb J_TCrumb">
                <div class="crumbCon">
                    <div class="crumbSlide J_TCrumbSlide">
                        <div class="crumbClip">
                            <ul class="crumbSlide-con clearfix J_TCrumbSlideCon">
                                <li>
                                    <a class="crumbStrong" href="javascript:;" title="已选筛选项" >已选筛选项</a><i class="crumbArrow">&gt;</i></li>
                                <?php if($attr_selected){ ?>
                                <?php  foreach($attr_selected as $value){ ?>
                                <li class="crumbAttr" title="取消选择">
                                    <a href="<?=$value['url']?>" ><?=$value['name']?></a>
                                    <a class="crumbDelete" href="<?=$value['url']?>" ></a>
                                </li>
                                <?php } ?>
                                <?php } ?>
                                <li class="crumbSearch">

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        </div>
            <div class="attrs">
                                <div class="propAttrs">
                                    <?php if($brand) { ?>
                                        <div class="attr J_TProp">
                                            <div class="attrKey">
                                                品牌
                                            </div>
                                            <div class="attrValues">
                                                <ul >
                                                    <?php foreach($brand as $v){ ?>
                                                        <li><a  href="<?=$v['url']?>" ><?=$v['name']?> (<?=$v['count']?>)</a></li>
                                                    <?php } ?>
                                                </ul>
                                                <div class="av-options">
                                                    <a  href="javascript:;" class="J_TMore avo-more ui-more-drop-l" >
                                                        更多<i class="ui-more-drop-l-arrow"></i>
                                                    </a>
                                                </div>
                                                <div class="av-btns">
                                                    <a href="javascript:;" class="J_TSubmitBtn ui-btn-s-primary ui-btn-disable">确定</a>
                                                    <a href="javascript:;" class="J_TCancelBtn ui-btn-s" >取消</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php foreach($attr as $key=>$value){ ?>
                                      <div class="attr J_TProp">
                                        <div class="attrKey">
                                           <?=$key?>
                                        </div>
                                        <div class="attrValues">
                                            <ul >
                                                <?php foreach($value as $v){ ?>
                                            <li><a  href="<?=$v['url']?>" data-value="<?=$v['value']?>"><?=$v['name']?> (<?=$v['count']?>)</a></li>
                                                <?php } ?>
                                            </ul>
                                            <div class="av-options">
                                                <a  href="javascript:;" class="J_TMore avo-more ui-more-drop-l" >
                                                    更多<i class="ui-more-drop-l-arrow"></i>
                                                </a>
                                            </div>
                                            <div class="av-btns">
                                                <a href="javascript:;" class="J_TSubmitBtn ui-btn-s-primary ui-btn-disable">确定</a>
                                                <a href="javascript:;" class="J_TCancelBtn ui-btn-s" >取消</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
            <div class="attrs-border"></div>
            <div class="attrExtra">
                <div class="attrExtra-border"></div>
                <a class="attrExtra-more J_TMoreAttrs"><i></i>更多选项</a>
            </div>
        </div>
            <div class="filter clearfix J_TFilter">
                <form id="filter_form" action="<?=Yii::$app->request->getAbsoluteUrl();?>" method="get">
                    <input type="hidden" name="page" value="1">
                    <a  href="<?= $sort_data['score'] ?>" class="fSort <?=$sort_selected=='score'?'fSort-cur':''?>" rel="nofollow" >综合排序</a>
                    <a href="<?= $sort_data['record'] ?>" class="fSort <?=$sort_selected=='record'?'fSort-cur':''?>" rel="nofollow" >销量<i class="f-ico-arrow-d"></i></a>
                    <a href="<?= $sort_data['favourite'] ?>" class="fSort <?=$sort_selected=='favourite'?'fSort-cur':''?>"  rel="nofollow">关注<i class="f-ico-arrow-d"></i></a>
                    <a  href="<?= $sort_data['price'] ?>" class="fSort <?=$sort_selected=='price'?'fSort-cur':''?>"  rel="nofollow" >价格
                        <i class="f-ico-triangle-mt <?=Yii::$app->request->get('order')=='asc'?'f-ico-triangle-mt-slctd':''?>"></i><i class="f-ico-triangle-mb <?=Yii::$app->request->get('order')=='desc'?'f-ico-triangle-mb-slctd':''?>"></i>
                    </a>
                    <div class="fPrice J_TFPrice">
                        <div class="fP-box">
                            <b class="fPb-item">
                                <i class="ui-price-plain">￥</i>
                                <input type="text" class="J_TFPInput lowPrice" value="<?=Yii::$app->request->get("lowPrice");?>" maxlength="8" autocomplete="off" name="lowPrice">
                            </b>
                            <i class="fPb-split"></i>
                            <b class="fPb-item">
                                <i class="ui-price-plain">￥</i>
                                <input type="text" class="J_TFPInput highPrice" maxlength="8" value="<?=Yii::$app->request->get("highPrice");?>" autocomplete="off" name="highPrice">
                            </b>
                        </div>
                        <div class="fP-expand">
                            <a class="ui-btn-s J_TFPCancel" href="javascript:;" >清空</a>
                            <a class="ui-btn-s-primary J_TFPEnter" href="javascript:void(0);" >确定</a>
                        </div>
                    </div>
                </form>
            </div>
           <div class="J_TItems">
            <?= \yii\widgets\ListView::widget([
                            'dataProvider' => $dataProvider,
                            'layout'=>'<div class="w clearfix">{items}</div><div class="tc m10">{pager}</div>',
                            'options' => ['class' => 'item5line1'],
                            'emptyTextOptions' => ['class' => 'empty tc p10 '],
                            'itemView' => '_item_grid_view',
             ]);
            ?>
           </div>
        </div>
    </div>
</div>
    </div>
</div>
    </div>
</div>
</div>
<?php $this->beginBlock('JS_END') ?>
//包装
jQuery(".picScroll-left-list").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",scroll:5,vis:5,trigger:"click"});
$(".picScroll-left-list.example1").each(function(){
var len=$(this).find("li").length;
if(len>4){
$(this).find(".hd").show()
}
});
$(".J_TFPEnter").bind('click',function(){
    $("#filter_form").submit();
});
$(".J_TFPCancel").bind("click",function(){
$(".J_TFPInput").attr("value","");
})
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>