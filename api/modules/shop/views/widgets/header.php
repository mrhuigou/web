<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/19
 * Time: 15:09
 */
use yii\helpers\Url;
?>
<div id="header" style="margin-top:0!important;">
    <!--top-->
    <div class="site-nav">
        <div class="site-nav-bd clearfix">
            <ul class="site-nav-bd-l clearfix">
                <li><a href="#">测试用户</a></li>
                <li><a href="#">消息</a></li>
            </ul>
            <ul class="site-nav-bd-r clearfix">
                <li><a href="#">购物车78</a></li>
                <li><a href="#">收藏夹</a></li>
                <li><a href="#">联系客服</a></li>
                <li><a href="#">网站导航</a></li>
            </ul>
        </div>
    </div>
    <!--logo和search-->
    <div class="header-box w990 bc">
        <div class="logo-search clearfix">
            <div class="logos fl">
                <a href="<?=Yii::$app->homeUrl?>"><img src="http://www.365jiarun.com/catalog/view/theme/web3.0/images/2015/hlogo.jpg" alt="慧生活logo" /></a>
            </div>
            <div id="mallSearch" class="mall-search fr">
                <form name="searchTop" action="http://api.jiarun.com/shop/category"  class="mallSearch-form">
                    <fieldset>
                        <div class="mallSearch-input clearfix">
                            <div class="defaultSearch">
                                <div id="s-combobox" class="s-combobox">
                                    <div class="s-combobox-input-wrap">
                                        <input type="text" name="keyword" accesskey="s" autocomplete="off" x-webkit-speech="" x-webkit-grammar="builtin:translate" value="" class="s-combobox-input" role="combobox" aria-haspopup="true" title="请输入搜索文字" aria-label="请输入搜索文字">
                                    </div>
                                </div>
                                <button id="J_SearchBtn" type="submit">搜索<s></s></button>
                            </div>
                            <input id="J_Type" type="hidden" name="shop_code" value="DP0001">
                        </div>
                    </fieldset>
                </form>
            </div>


        </div>
        <?php if(isset($data['store']) &&  $data['store']){ ?>
            <div class="shop-summary clearfix">
                        <span class="line-left fl">
                            <a href="<?=Url::to(['/shop/index','shop_code'=>$data['store']->store_code])?>" class="mr10">店铺： <?=$data['store']->name?></a>
                            <span class="mini-dsr gray6"  rel="nofollow">
                            <i class="grayc">[</i>
                                <span class="dsr-title">描述</span>
                                <span class="dsr-num red">4.7</span>
                                |
                                <span class="dsr-title">服务</span>
                                <span class="dsr-num red">4.8</span>
                                |
                                <span class="dsr-title">物流</span>
                                <span class="dsr-num green">4.7</span>
                            <i class="grayc">]</i>
                            </span>
                        </span>
                        <span class="line-right fr">
                            <a href="#">收藏店铺</a>
                        </span>
            </div>
        <?php } ?>
    </div>
</div>