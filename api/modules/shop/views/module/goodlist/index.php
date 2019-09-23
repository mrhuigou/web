<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<div class="J_TModule" data-title="搜索列表" data-spm-max-idx="322">
<div class="skin-box tb-module tshop-pbsm tshop-pbsm-tmall-srch-list" id="TmshopSrchNav">
<div class="skin-box-bd">

<div class="crumb J_TCrumb">
    <div class="crumbCon">
    <div class="crumbSlide J_TCrumbSlide">
    <div class="crumbClip">
        <ul class="crumbSlide-con clearfix J_TCrumbSlideCon">
            <li>
                <a class="crumbStrong" href="javascript:;" title="已选筛选项" >已选筛选项</a><i class="crumbArrow">&gt;</i></li>
            <?php if($data['data']['attr_selected']){ ?>
            <?php  foreach($data['data']['attr_selected'] as $value){ ?>
            <li class="crumbAttr" title="取消选择">
                <a href="<?=$value['url']?>" ><?=$value['name']?></a>
                <a class="crumbDelete" href="<?=$value['url']?>" ></a>
            </li>
            <?php } ?>
            <?php } ?>
            <li class="crumbSearch">
                <form class="J_TCrumbSearchForm" action="<?=Url::to('/shop/category',true)?>" method="get">
                    <label class="crumbSearch-label" for="J_CrumbSearchInuput">
                        <?php foreach(\Yii::$app->request->get() as $key=>$value){ ?>
                            <?php if(is_array($value)) {?>
                                <?php foreach($value as  $k=>$v){ ?>
                                   <input type="hidden" value="<?=$v?>" name="<?=$key?>[<?=$k?>]">
                                <?php } ?>
                            <?php }elseif(!in_array($key,['keyword','page'])){ ?>
                                <input type="hidden" value="<?=$value?>" name="<?=$key?>">
                            <?php } ?>
                            <input type="hidden" value="0" name="page">
                        <?php } ?>
                        <input type="text" class="crumbSearch-input J_TCrumbSearchInuput" value="<?=\Yii::$app->request->get('keyword')?>" name="keyword">
                    </label>
                    <input type="submit" class="crumbSearch-btn J_TCrumbSearchBtn" >
                </form>
            </li>
        </ul>
    </div>
    </div>
    </div>
    </div>

    <?php if(isset($data['data']['attr']) && $data['data']['attr']) { ?>
        <div class="attrs">
                        <div class="propAttrs">
                            <?php if($data['data']['brand']) { ?>
                                <div class="attr J_TProp">
                                    <div class="attrKey">
                                        品牌
                                    </div>
                                    <div class="attrValues">
                                        <ul >
                                            <?php foreach($data['data']['brand'] as $v){ ?>
                                                <li><a  href="<?=$v['url']?>" data-value="<?=$v['id']?>"><?=$v['name']?> (<?=$v['count']?>)</a></li>
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
                            <?php foreach($data['data']['attr'] as $key=>$value){ ?>
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
                            <div class="J_TMoreAttrsCont">
                                <?php foreach($data['data']['attr_sub'] as $key=>$value){ ?>
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
                        </div>
    <div class="attrs-border"></div>
                <div class="attrExtra">
                    <div class="attrExtra-border"></div>
                    <a class="attrExtra-more J_TMoreAttrs"><i></i>更多选项</a>
                </div>
        </div>
    <?php } ?>
    <div class="filter clearfix J_TFilter">
        <form action="">
            <a  href="<?=$data['data']['sort']['score']?>" class="fSort <?=$data['data']['sort_selected']=='score'?'fSort-cur':''?>" rel="nofollow" >综合排序</a>
            <a href="<?=$data['data']['sort']['record']?>" class="fSort <?=$data['data']['sort_selected']=='record'?'fSort-cur':''?>" rel="nofollow" >销量<i class="f-ico-arrow-d"></i></a>
            <a href="<?=$data['data']['sort']['favourite']?>" class="fSort <?=$data['data']['sort_selected']=='favourite'?'fSort-cur':''?>"  rel="nofollow">收藏<i class="f-ico-arrow-d"></i></a>
            <a  href="<?=$data['data']['sort']['price']?>" class="fSort <?=$data['data']['sort_selected']=='price'?'fSort-cur':''?>"  rel="nofollow" >价格
                <i class="f-ico-triangle-mt <?=$data['data']['sort_order']=='asc'?'f-ico-triangle-mt-slctd':''?>"></i><i class="f-ico-triangle-mb <?=$data['data']['sort_order']=='desc'?'f-ico-triangle-mb-slctd':''?>"></i>
            </a>
            <div class="fPrice J_TFPrice">
                <div class="fP-box">
                    <b class="fPb-item">
                        <i class="ui-price-plain">￥</i>
                        <input type="text" class="J_TFPInput" value="" maxlength="8" autocomplete="off" name="lowPrice">
                    </b>
                    <i class="fPb-split"></i>
                    <b class="fPb-item">
                        <i class="ui-price-plain">￥</i>
                        <input type="text" class="J_TFPInput" maxlength="8" value="" autocomplete="off" name="highPrice">
                    </b>
                </div>
                <div class="fP-expand">
                    <a class="ui-btn-s J_TFPCancel" href="javascript:;" >清空</a>
                    <a class="ui-btn-s-primary J_TFPEnter" href="javascript:;" >确定</a>
                </div>
            </div>
        </form>
        <a href="<?=$data['data']['style']['grid']?>" class="fType-g <?=$data['data']['style']['cur']=='grid'?'fType-cur':''?>" >大图<i class="fTg-ico"></i></a>
        <a href="<?=$data['data']['style']['list']?>" class="fType-l <?=$data['data']['style']['cur']=='list'?'fType-cur':''?>" >列表<i class="fTl-ico "></i></a>
        <?= LinkPager::widget(['pagination' => $data['data']['pages'],'maxButtonCount'=>0,'options'=>['class'=>'pagination ui-page-s']]); ?>
    </div>
    <?php if(isset($data['data']['viewType']) && $data['data']['viewType']=='grid'){ ?>
            <div class="J_TItems">
                <?php if(isset($data['data']['products'])&& $data['data']['products']) { ?>
                    <div class="item4line1">
                    <?php foreach($data['data']['products'] as $key=>$product){  ?>
                    <dl class="item <?=$key%4==3?"last":""?>" >
                    <dt class="photo">
                        <a  href="<?=Url::to(['/shop/detail','shop_code'=>$product['store_code'],'product_base_code'=>$product['product_code']],true)?>" target="_blank" >
                         <img alt="" src="<?=$product['image']?>">
                        </a>
					</dt>
                        <?php if($product['sub_image']) { ?>
                    <dd class="thumb">

                        <!--包装-->
                        <div class="picScroll-left-list example1">
                            <div class="hd clearfix">
                                <a class="next">></a>
                                <a class="prev"><</a>
                            </div>
                            <div class="bd">
                                <ul class="picList clearfix">
                                    <?php foreach($product['sub_image'] as $sub){ ?>
                                    <li>
                                        <div class="pic"><a href="javascript:;"data-sku="<?=$sub->sku?>" > <img  src="<?=\common\component\image\Image::resize($sub->image,50,50)?>"></a></div>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                     </dd>
                        <?php } ?>
                     <dd class="detail">
                        <a class="item-name"  href="<?=Url::to(['/shop/detail','shop_code'=>$product['store_code'],'product_base_code'=>$product['product_code']],true)?>" target="_blank" ><?=$product['product_name']?> </a>
                        <div class="attribute">
                            <div class="cprice-area"><span class="symbol">¥</span><span class="c-price"><?=$product['price']?></span></div>
                       </div>
                    </dd>
                    </dl>
                    <?php } ?>
                    </div>
                <div class="tc p20"><?= LinkPager::widget(['pagination' => $data['data']['pages'],'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页']); ?></div>
                <?php } else{ ?>
                    <div class="tc p20">Hi～没有找到相关商品！</div>
                <?php } ?>
            </div>

    <?php }else{ ?>
    <div class="J_TItems">
        <div class="size80">
            <?php if(isset($data['data']['products'])&& $data['data']['products']) { ?>
            <ul class="items">
                <?php foreach($data['data']['products'] as $key=>$product){  ?>
                <li class="item-wrap">
                    <dl class="item">
                        <dt class="pic">
                            <a  href="<?=Url::to(['/shop/detail','shop_code'=>$product['store_code'],'product_base_code'=>$product['product_code']],true)?>" target="_blank" >
                                <img alt="" src="<?=$product['image']?>">
                            </a>
                        </dt>
                        <dd class="detail-info">
                            <p class="title">
                                <a class="item-name"  href="<?=Url::to(['/shop/detail','shop_code'=>$product['store_code'],'product_base_code'=>$product['product_code']],true)?>" target="_blank" ><?=$product['product_name']?> </a>
                            </p>

                            <p class="price">
                                <span class="after-discount"><span class="symbol">¥</span><span class="value"><?=$product['price']?></span></span>
                            </p>
                        </dd>
                    </dl>
                </li>
                <?php } ?>
            </ul>
                <div class="tc p20"><?= LinkPager::widget(['pagination' => $data['data']['pages'],'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页']); ?></div>
            <?php } else{ ?>
                <div class="tc p20">Hi～没有找到相关商品！</div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

</div>
    </div>
</div>