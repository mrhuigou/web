<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php if(isset($styles)){ foreach ($styles as $style) { ?>
        <link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
        <?php }}else{?>
		<link rel="stylesheet" type="text/css" href="assets/stylesheets/global.css"  />
		<link rel="stylesheet" type="text/css" href="assets/stylesheets/layout.css"  />
        <link rel="stylesheet" type="text/css" href="assets/stylesheets/modules.css" />
		<link rel="stylesheet/less" type="text/css" href="assets/stylesheets/page.less" />
		<link rel="stylesheet/less" type="text/css" href="assets/stylesheets/suggest.css" />


        
        <!--lessjs-->
        <script type="text/javascript" src="assets/script/less-1.7.3.min.js"></script>
        
		<!--皮肤
		<link rel="stylesheet" href="http://a.tbcdn.cn/??/apps/taesite/platinum/stylesheet/common/templates/tmall/black/skin.css?t=20121031.css">-->
		<!--店招 关注-->
		<link rel="stylesheet" href="http://img04.taobaocdn.com/bao/uploaded/i4/TB10yJUGFXXXXb7XpXXn00bFXXX.css" />
		<!--详情页-->
		<link rel="stylesheet" href="http://g.tbcdn.cn/tm/shop/1.2.12/page/detail.css" />
		

		<!--
		<script>
			window.g_hb_monitor_st = +new Date();
			window.g_config = {appId: 2, assetsHost: "http://a.tbcdn.cn/", toolbar: false, pageType: "tmall", sysId:"shop", shopId: "62139851",sellerId: "445704952",wtId:"2067601266",showShopQrcode:true};
			window.shop_config = {
				"shopId": "62139851",
				"siteId": "2",
				"userId": "445704952",
				"user_nick": "sdeerconcept%E6%97%97%E8%88%B0%E5%BA%97",
							"template": {
					"name": "",
					"id": "4",
					"design_nick": ""
				}
			};
			window.shop_config.isView = true;
			window.shop_config.isvStat = {
				nickName: 'sdeerconcept%E6%97%97%E8%88%B0%E5%BA%97',
				userId: '445704952',
				shopId: '62139851',
				siteId: '2',
				siteCategoryId: '3',
				itemId: '',
				shopStats: '1',
				validatorUrl: 'http://store.taobao.com/tadget/shop_stats.htm',
				templateId: '4',
				templateName: ''
			};
			window._poc = window._poc || [];
			window._poc.push(["_trackCustom", "tpl", "new_shop"]);
		</script>
	  
		<script src="http://g.tbcdn.cn/??kissy/k/1.3.0/seed-min.js,mui/seed/1.1.11/seed.js,mui/seed-g/1.0.69/seed.js,mui/btscfg-g/1.3.0/index.js,mui/bucket/1.2.2/index.js,mui/globalmodule/1.3.42/global-module.js,mui/global/1.2.42/global.js,tm/detail/1.6.30/app.js,tm/detail/1.6.30/page/shop.js"></script>

		<script src="http://g.tbcdn.cn/shop/wangpu/1.0.35/init-min.js?t=20141011.js"></script>

		<script>
			var login_config = {
				loginUserId:"0",
				loginUserNick:"",
				b2cLightAll:false,
				c2cLightAll:false,
				allLightAll:false,
				q75:false,
				currentTime:"2014-12-01-14-01-59",
				isInternal:false,
				isAudit:false
			}
		</script>

		<script src="http://g.alicdn.com/kissy/k/5.0.1/seed-debug.js" data-config="{combine:false}"></script>
		-->

	
		
		
	<?php }?>
    </head>
    <body>
		<div id="page">
			<div id="header" style="margin-top:0!important;">
                <!--top-->
                <div class="site-nav">
                  <div class="site-nav-bd clearfix">
                    <ul class="site-nav-bd-l clearfix">
                      <li><a href="#">jingliping_pp</a></li>
                      <li><a href="#">消息</a></li>
                    </ul>

                    <ul class="site-nav-bd-r clearfix">
                      <li class="sn-menu">
                        <a class="menu-hd" rel="nofollow">我的家润<b></b></a>
                        <div class="menu-bd">
                            <a rel="nofollow">已买到的宝贝</a>
                            <a rel="nofollow">已卖出的宝贝</a>
                        </div>
                      </li>
                      <li class="sn-mobile">
                        APP
                        <div class="sn-qrcode">
                            <img src="http://www.365jiarun.com/catalog/view/theme/web3.0/images/weibo.png" alt="家润慧生活-微博二维码" width="120" height="120" class="db">
                            <b></b>
                        </div>    
                      </li>
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
                            <a href="#"><img src="http://www.365jiarun.com/catalog/view/theme/web3.0/images/2015/hlogo.jpg" alt="慧生活logo" /></a>
                        </div>



						<div id="mallSearch" class="mall-search fr">
			                <form name="searchTop" action="http://list.tmall.com/search_product.htm"
			                      class="mallSearch-form">
			                    <fieldset>
			                        <div class="mallSearch-input clearfix">
			                            <div class="defaultSearch">
			                                <div id="s-combobox" class="s-combobox">
			                                    <div class="s-combobox-input-wrap">
			                                        <input type="text" name="q" accesskey="s" autocomplete="off" x-webkit-speech="" x-webkit-grammar="builtin:translate" value="" class="s-combobox-input" role="combobox" aria-haspopup="true" title="请输入搜索文字" aria-label="请输入搜索文字">
			                                    </div>
			                                    </div>
			                                <button id="J_SearchBtn" type="submit">搜索<s></s></button>
			                            </div>
			                            <button id="J_CurrShopBtn" class="currShopBtn none" type="button">搜本店<s></s></button>
			                            <input id="J_Type" type="hidden" name="type" value="p">
			                            <input id="J_MallSearchStyle" type="hidden" name="style" value="">
			                            <input id="J_Cat" type="hidden" name="cat" value="all">
			                        </div>
			                    </fieldset>
			                </form>
			            </div>





                        <div class="search-box fr pr clearfix none">
                            <input type="text" class="fl related-input" />
                            <ul class="related-keywords none">
                                <li>111</li>
                                <li>222</li>
                                <li>333</li>
                                <li>444</li>
                                <li>555</li>
                                <li>666</li>
                            </ul>   
                            <button class="btn-search fr" type="submit">搜索</button>
                        </div>


                       




                    </div>

                    <div class="shop-summary clearfix">
                        <span class="line-left fl">
                            <a href="#" class="mr10">店铺： MIUCO TB 欧美女装每天上</a>
                            <span class="mini-dsr gray6" href="#" rel="nofollow">
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
                </div>


            </div>
			<div id="content" style="background-color:#7e7e7e;">
				<div id="hd">
					<?php foreach($hd as $value){
					echo $value;
					}?>
				</div>
				<div id="bd" style="width:100%">
					<?php foreach($bd as $value){
					echo $value;
					}?>
				</div>
				<div id="ft">
					<?php foreach($ft as $value){
					echo $value;
					}?>
				</div>
			</div>
			<div id="footer">
                <div class="w990 bc">
                    <div class="g_foot-ali">
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                        <a href="#">慧生活</a>
                        <b>|</b>
                    </div>
                    <div class="g_foot-nav">
                        <a href="#">关于慧生活</a>
                        <a href="#">合作伙伴</a>
                        <a href="#">营销中心</a>
                        <a href="#">联系客服</a>
                        <a href="#">开放平台</a>
                        <a href="#">诚征英才</a>
                        <span class="grayc">家润电子科技有限公司版权所有</span>
                    </div>
                </div>
			</div>
		</div>
		
		
		
		
		
		
		
		
		
		<div class="tshop-pbsm-shop-nav-ch"><div class="skin-box-bd" style="width: 0px; height: 0px;"><div class="popup-content all-cats-popup">
                    <div class="popup-inner">
                                                                                                                                                                                                                            
                        <ul class="J_TAllCatsTree cats-tree">
                            <li class="cat fst-cat">
                                <h4 class="cat-hd fst-cat-hd has-children">
                                                                    <i class="cat-icon fst-cat-icon"></i>
                                    <a href="http://broadcast.tmall.com/search.htm?search=y" class="cat-name fst-cat-name">所有宝贝</a>
                                </h4>

                                <div class="snd-pop">
                                    <div class="snd-pop-inner">
                                        <ul class="fst-cat-bd">
                                            <li class="cat snd-cat">
                                                                                                    <h4 class="cat-hd snd-cat-hd">
                                                        <i class="cat-icon snd-cat-icon"></i>
                                                        <a href="http://broadcast.tmall.com/search.htm?search=y&amp;orderType=defaultSort" class="by-label by-sale snd-cat-name" rel="nofollow">按综合</a>
                                                    </h4>
                                                                                                <h4 class="cat-hd snd-cat-hd">
                                                    <i class="cat-icon snd-cat-icon"></i>
                                                    <a href="http://broadcast.tmall.com/search.htm?search=y&amp;orderType=hotsell_desc" class="by-label by-sale snd-cat-name" rel="nofollow">按销量</a>
                                                </h4>
                                                <h4 class="cat-hd snd-cat-hd">
                                                    <i class="cat-icon snd-cat-icon"></i>
                                                    <a href="http://broadcast.tmall.com/search.htm?search=y&amp;orderType=newOn_desc" class="by-label by-new snd-cat-name" rel="nofollow">按新品</a>
                                                </h4>
                                                <h4 class="cat-hd snd-cat-hd">
                                                    <i class="cat-icon snd-cat-icon"></i>
                                                    <a href="http://broadcast.tmall.com/search.htm?search=y&amp;orderType=price_asc" class="by-label by-price snd-cat-name" rel="nofollow">按价格</a>
                                                </h4>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="cat fst-cat">
                                <h4 class="cat-hd fst-cat-hd ">
                                
                                    <i class="cat-icon fst-cat-icon  active-trigger"></i>
                                    <a class="cat-name fst-cat-name" href="http://broadcast.tmall.com/category-979490726.htm?search=y&amp;catName=%C8%AB%B2%BF%C9%CC%C6%B7">全部商品</a>
                                </h4>
                            </li>
                            <li class="cat fst-cat">
                                <h4 class="cat-hd fst-cat-hd has-children">
                                
                                    <i class="cat-icon fst-cat-icon  active-trigger"></i>
                                    <a class="cat-name fst-cat-name" href="http://broadcast.tmall.com/category-855694245.htm?search=y&amp;catName=%D3%F0%C8%DE%B7%FE">羽绒服</a>
                                </h4>
                                <div class="snd-pop">
                                    <div class="snd-pop-inner">
                                        <ul class="fst-cat-bd">
                                                                                                    <li class="cat snd-cat">
                                                    <h4 class="cat-hd snd-cat-hd">
                                                        <i class="cat-icon snd-cat-icon"></i>
                                                        <a class="cat-name snd-cat-name" href="http://broadcast.tmall.com/category-855694246.htm?search=y&amp;parentCatId=855694245&amp;parentCatName=%D3%F0%C8%DE%B7%FE&amp;catName=%B6%CC%BF%EE%D3%F0%C8%DE%B7%FE">
                                                            短款羽绒服
                                                        </a>
                                                    </h4>
                                                </li>
                                                                                                    <li class="cat snd-cat">
                                                    <h4 class="cat-hd snd-cat-hd">
                                                        <i class="cat-icon snd-cat-icon"></i>
                                                        <a class="cat-name snd-cat-name" href="http://broadcast.tmall.com/category-855694247.htm?search=y&amp;parentCatId=855694245&amp;parentCatName=%D3%F0%C8%DE%B7%FE&amp;catName=%D6%D0%B3%A4%BF%EE%D3%F0%C8%DE%B7%FE">
                                                            中长款羽绒服
                                                        </a>
                                                    </h4>
                                                </li>
                                                                                                    <li class="cat snd-cat">
                                                    <h4 class="cat-hd snd-cat-hd">
                                                        <i class="cat-icon snd-cat-icon"></i>
                                                        <a class="cat-name snd-cat-name" href="http://broadcast.tmall.com/category-1000298448.htm?search=y&amp;parentCatId=855694245&amp;parentCatName=%D3%F0%C8%DE%B7%FE&amp;catName=%D3%F0%C8%DE%C2%ED%BC%D7">
                                                            羽绒马甲
                                                        </a>
                                                    </h4>
                                                </li>
                                                                                            </ul>
                                    </div>
                                </div>
                            </li>
                       
          
                                                            <li class="cat fst-cat">
                                    <h4 class="cat-hd fst-cat-hd ">
                                    
                                        <i class="cat-icon fst-cat-icon  active-trigger"></i>
                                        <a class="cat-name fst-cat-name" href="http://broadcast.tmall.com/category-861633879.htm?search=y&amp;catName=%B5%F5%B4%F8%C9%C0">吊带衫</a>
                                                                        </h4>
                                                                    </li>
              
         
                                                            <li class="cat fst-cat">
                                    <h4 class="cat-hd fst-cat-hd ">
                                    
                                        <i class="cat-icon fst-cat-icon  active-trigger"></i>
                                        <a class="cat-name fst-cat-name" href="http://broadcast.tmall.com/category-681546330.htm?search=y&amp;catName=%BE%AB%D1%A1%C8%C8%C2%F4%BF%EE">精选热卖款</a>
                                                                        </h4>
                                                                    </li>
          
     
                       
                                                    </ul>
                    </div>
                </div><div class="popup-content">
                            <ul class="menu-popup-cats">
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-855694246.htm?search=y&amp;parentCatId=855694245&amp;parentCatName=%D3%F0%C8%DE%B7%FE&amp;catName=%B6%CC%BF%EE%D3%F0%C8%DE%B7%FE" class="cat-name" rel="nofollow">短款羽绒服</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-855694247.htm?search=y&amp;parentCatId=855694245&amp;parentCatName=%D3%F0%C8%DE%B7%FE&amp;catName=%D6%D0%B3%A4%BF%EE%D3%F0%C8%DE%B7%FE" class="cat-name" rel="nofollow">中长款羽绒服</a></li>
                                                            </ul>
                        </div><div class="popup-content">
                            <ul class="menu-popup-cats">
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-952026019.htm?search=y&amp;parentCatId=861630025&amp;parentCatName=%CD%E2%CC%D7&amp;catName=%C3%AB%C4%D8%B4%F3%D2%C2" class="cat-name" rel="nofollow">毛呢大衣</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-952026020.htm?search=y&amp;parentCatId=861630025&amp;parentCatName=%CD%E2%CC%D7&amp;catName=%C3%DE%D2%C2%C3%DE%B7%FE" class="cat-name" rel="nofollow">棉衣棉服</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-952026022.htm?search=y&amp;parentCatId=861630025&amp;parentCatName=%CD%E2%CC%D7&amp;catName=%B7%E7%D2%C2" class="cat-name" rel="nofollow">风衣</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-952026018.htm?search=y&amp;parentCatId=861630025&amp;parentCatName=%CD%E2%CC%D7&amp;catName=%CE%F7%D7%B0" class="cat-name" rel="nofollow">西装</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-952026021.htm?search=y&amp;parentCatId=861630025&amp;parentCatName=%CD%E2%CC%D7&amp;catName=%C6%A4%D2%C2%C6%A4%B2%DD" class="cat-name" rel="nofollow">皮衣皮草</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-952026023.htm?search=y&amp;parentCatId=861630025&amp;parentCatName=%CD%E2%CC%D7&amp;catName=%C2%ED%BC%D7" class="cat-name" rel="nofollow">马甲</a></li>
                                                            </ul>
                        </div><div class="popup-content">
                            <ul class="menu-popup-cats">
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-978239751.htm?search=y&amp;parentCatId=861633871&amp;parentCatName=%D5%EB%D6%AF%C9%C0&amp;catName=%D5%EB%D6%AF%BF%AA%C9%C0" class="cat-name" rel="nofollow">针织开衫</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-978239752.htm?search=y&amp;parentCatId=861633871&amp;parentCatName=%D5%EB%D6%AF%C9%C0&amp;catName=%CC%D7%CD%B7%C3%AB%D2%C2" class="cat-name" rel="nofollow">套头毛衣</a></li>
                                                            </ul>
                        </div><div class="popup-content">
                            <ul class="menu-popup-cats">
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-262729292.htm?search=y&amp;parentCatId=249750554&amp;parentCatName=%BF%E3%D7%B0&amp;catName=%D0%DD%CF%D0%BF%E3" class="cat-name" rel="nofollow">休闲裤</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-693773087.htm?search=y&amp;parentCatId=249750554&amp;parentCatName=%BF%E3%D7%B0&amp;catName=%C6%DF%2F%BE%C5%B7%D6%BF%E3" class="cat-name" rel="nofollow">七/九分裤</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-324915350.htm?search=y&amp;parentCatId=249750554&amp;parentCatName=%BF%E3%D7%B0&amp;catName=%B9%FE%C2%D7%2F%C0%AB%CD%C8%BF%E3" class="cat-name" rel="nofollow">哈伦/阔腿裤</a></li>
                                                                    <li class="sub-cat"><a href="http://broadcast.tmall.com/category-952026024.htm?search=y&amp;parentCatId=249750554&amp;parentCatName=%BF%E3%D7%B0&amp;catName=%B4%F2%B5%D7%BF%E3" class="cat-name" rel="nofollow">打底裤</a></li>
                                                            </ul>
                        </div><div class="popup-content">
               
                        </div></div></div>
		
		
		
		<ul class="pagination"><li class="prev disabled"><span>上一页</span></li>
<li class="active"><a data-page="0" href="/shop/category?shop_code=DP0001&amp;page=1&amp;per-page=12">1</a></li>
<li><a data-page="1" href="/shop/category?shop_code=DP0001&amp;page=2&amp;per-page=12">2</a></li>
<li><a data-page="2" href="/shop/category?shop_code=DP0001&amp;page=3&amp;per-page=12">3</a></li>
<li><a data-page="3" href="/shop/category?shop_code=DP0001&amp;page=4&amp;per-page=12">4</a></li>
<li><a data-page="4" href="/shop/category?shop_code=DP0001&amp;page=5&amp;per-page=12">5</a></li>
<li><a data-page="5" href="/shop/category?shop_code=DP0001&amp;page=6&amp;per-page=12">6</a></li>
<li><a data-page="6" href="/shop/category?shop_code=DP0001&amp;page=7&amp;per-page=12">7</a></li>
<li><a data-page="7" href="/shop/category?shop_code=DP0001&amp;page=8&amp;per-page=12">8</a></li>
<li><a data-page="8" href="/shop/category?shop_code=DP0001&amp;page=9&amp;per-page=12">9</a></li>
<li><a data-page="9" href="/shop/category?shop_code=DP0001&amp;page=10&amp;per-page=12">10</a></li>
<li class="next"><a data-page="1" href="/shop/category?shop_code=DP0001&amp;page=2&amp;per-page=12">下一页</a></li></ul>
		
		
		
		
		
		
		<script type="text/javascript" src="assets/script/jq.min.js"></script>
		<script type="text/javascript" src="assets/script/jquery.SuperSlide.2.1.1.js"></script>
		<script type="text/javascript" src="http://g.tbcdn.cn/kissy/k/1.3.2/seed.js"></script>
        
		<script type="text/javascript">
			$(document).ready(function(){
				//tab切换
				$(".top-list-tab li").mouseover(function(){
					var _this=$(this),
						idx=_this.index(),
						con=_this.parent().siblings(".panels").find(".panel");
					console.log(idx);
					_this.addClass("selected").siblings().removeClass("selected");
					con.eq(idx).removeClass("disappear").siblings(".panel").addClass("disappear");
				});
				
				//宝贝分类
				$(".cats-tree .fst-cat-icon").toggle(function(){
					var _this=$(this),
						box=_this.parents(".cats-tree"),
						fst_hd=_this.parent("h4"),
						con=fst_hd.next("ul");
					box.find(".fst-cat-icon").removeClass("ks-switchable-select");
					_this.addClass("ks-switchable-select");
					_this.removeClass("active-trigger");
					con.hide();
				},function(){
					var _this=$(this),
					fst_hd=_this.parent("h4"),
					con=fst_hd.next("ul");
					_this.addClass("active-trigger");
					con.show();
				});
				
				//幻灯片
				jQuery(".slide-box").slide({mainCell:".slide-content",effect:"left",autoPlay:true});
				
				//所有商品
				$(".all-cats-popup .cats-tree li").hover(function(){
					var _this=$(this),
						offx=_this.offset().left,
						offy=_this.offset().top;
					$(this).find(".snd-pop").css({"left":188,"top":0}).show();
				},function(){
					$(this).find(".snd-pop").css({"left":9999,"top":9999}).hide();
				});
				
				//导航
				$(".tshop-pbsm-shop-nav-ch .popup-container").hover(function(){
					var _this=$(this),
						offx=_this.offset().left,
						offy=_this.offset().top,
						idx=_this.index();
					$(".tshop-pbsm-shop-nav-ch .popup-content").eq(idx).css({"left":offx,"top":offy+30,"z-index":201}).show();
				},function(){
					$(".tshop-pbsm-shop-nav-ch .popup-content").hide();
				})
				$(".tshop-pbsm-shop-nav-ch .popup-content").hover(function(){
					$(this).show();
				},function(){
					$(this).hide();
				});
				
				
				//更多选项
				$(".tshop-pbsm-tmall-srch-list .attrExtra-more").toggle(function(){
					$(this).html("<i></i>精简选项").addClass('attrExtra-more-drop').parents(".attrs").find(".J_TMoreAttrsCont").show();
				},function(){
					$(this).html("<i></i>更多选项").removeClass("attrExtra-more-drop").parents(".attrs").find(".J_TMoreAttrsCont").hide();
				})

                //更多
                var ul=$(".tshop-pbsm-tmall-srch-list .attrValues ul");
                ul.each(function(){
                    var _this=$(this);
                    if(_this.height()>50){
                        _this.addClass('av-collapse');
                        _this.parent().find(".avo-more").show();

                    }else if(_this.height()<50){
                        _this.addClass('av-collapse');
                    }
                })

                $(".J_TMoreAttrsCont").hide();
                
                
                $(".avo-more").click(function() {
                    var _this=$(this);
                    if(_this.is(".ui-more-drop-l")){
                        _this.parents(".attrValues").find(".av-collapse").removeClass('av-collapse').addClass('av-expand');
                        _this.removeClass('ui-more-drop-l').addClass('ui-more-expand-l').html("收起<i class='ui-more-expand-l-arrow'></i>");
                    }else if(_this.is(".ui-more-expand-l")){
                        _this.parents(".attrValues").find(".av-expand").removeClass('av-expand').addClass('av-collapse');
                        _this.removeClass('ui-more-expanddrop-l').addClass('ui-more-drop-l').html("更多<i class='ui-more-drop-l-arrow'></i>");
                    }
                });


                //多选
                $(".tshop-pbsm-tmall-srch-list .avo-multiple").click(function(){
                    var _this=$(this);
                    $(".J_TProp").removeClass('forMultiple');
                    _this.parents(".J_TProp").addClass('forMultiple');
                    $(".attrValues ul").removeClass('av-expand').addClass('av-collapse'); 
                    _this.parents(".attrValues").find("ul").addClass('av-expand').removeClass('av-collapse');
                    $(".avo-more").removeClass('ui-more-expand-l').addClass('ui-more-drop-l').html("更多<i class='ui-more-drop-l-arrow'></i>");
                })
                //取消
                $(".J_TCancelBtn").click(function() {
                    var _this=$(this);
                    _this.parents(".J_TProp").removeClass('forMultiple');
                    _this.parents(".attrValues").find("ul").removeClass('av-expand').addClass('av-collapse');
                    _this.parents(".attrValues").find(".avo-more").removeClass('ui-more-expand-l').addClass('ui-more-drop-l').html("更多<i class='ui-more-drop-l-arrow'></i>");

                    _this.parents(".attrValues").find("ul li").removeClass('av-selected');

                });



                


                $(".forMultiple ul li").live("click",function() {

                    if($(this).hasClass('av-selected')){
                        $(this).removeClass('av-selected').find("a i").remove();
                    }else{
                        $(this).addClass('av-selected').find("a").append('<i></i>');
                    }

                     if($(this).parent("ul").find('.av-selected').length){
                        $(this).parents(".forMultiple").find('.J_TSubmitBtn').removeClass('ui-btn-disable');
                        
                    }else{
                        $(this).parents(".forMultiple").find('.J_TSubmitBtn').addClass('ui-btn-disable');
                        
                    }

                });


             

                //搜索框点击全选内容
                $(".crumbSearch-input").click(function(event) {
                    $(this).select();
                });

                //综合排序价格弹层
                $(".J_TFPInput").click(function(e) {
                    e.stopPropagation();
                    $(this).parents(".J_TFPrice").addClass('fPrice-hover');
                });

                $("body").click(function(e) {
                    e.stopPropagation();
                    if(e.target!=$(".J_TFPCancel")[0] && e.target!=$(".J_TFPEnter")[0] &&e.target!=$(".J_TFPInput")[0]){
                       $(".J_TFPrice").removeClass('fPrice-hover');  
                    }
                    
                });

                $(".J_TFPCancel").click(function() {
                    //$(this).parents(".J_TFPrice").addClass('fPrice-hover');
                   // alert(1);
                   $(this).parent(".fP-expand").prev(".fP-box").find("input").val("");
                });


                //下拉列表
                $(".related-input").focus(function(){
                    $(".related-keywords").show();
                    $(".related-keywords li").hover(function(){
                        $(this).addClass("cur");
                    },function(){
                        $(this).removeClass("cur");
                    }).click(function(){
                        var text=$(this).text();
                        $(".related-input").val(text);
                    });
                });
                $(document).click(function(e){
                    if(e.target!=$(".related-input")[0]){
                        $(".related-keywords").hide();
                    }
                });

                //包装
                jQuery(".picScroll-left-list").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",scroll:4,vis:4,trigger:"click"});
                <!--包装-->
                $(".picScroll-left-list.example1").each(function(){
                    var len=$(this).find("li").length;
                    if(len>4){
                        $(this).find(".hd").show()
                    }
                });

                $(".picList .pic img").click(function(){
                    var _this=$(this);
                    var src=_this.attr("src");
                    _this.parents("dd").prev("dt").find("img").attr("src",src);
                });






                 KISSY.use("event,combobox", function (S, Event, ComboBox, SearchMenuItem) {

				    /*
				     remote dataSource
				     */
				    (function () {

				        var tmpl = "<div class='s-mi-list'>" +
				            "<span class='s-mi-cont-key'>{text}</span>" +
				            "<span class='s-mi-cont-count'>约{count}个宝贝</span>" +
				            "<a class='s-mi-btn-backfill' href='javascript:;'>回填</a></div>";

				        var basicComboBox = new ComboBox({
				            prefixCls: 's-',
				            placeholder: '',
				            srcNode: S.one("#mallSearch"),
				            width: 375,
				            dataSource: new ComboBox.RemoteDataSource({
				                xhrCfg: {
				                    url: 'http://suggest.taobao.com/sug',
				                    dataType: 'jsonp',
				                    data: {
				                        k: 1,
				                        code: "utf-8"
				                    }
				                },
				                paramName: "q",
				                parse: function (query, results) {
				                    // 返回结果对象数组
				                    return results.result;
				                },
				                cache: true
				            }),
				            format: function (query, results) {
				                var ret = [];
				                S.each(results, function (r) {
				                    var item = {
				                        // 点击菜单项后要放入 input 中的内容
				                        textContent: r[0],
				                        // 菜单项的
				                        content: S.substitute(tmpl, {
				                            text: r[0],
				                            count: r[1]
				                        })
				                    };
				                    ret.push(item);
				                });
				                return ret;
				            }
				        });
				        basicComboBox.render();

				        var html = '<div class="s-mi-tip" >'+
				                    '<i></i>找“<b>dd</b>”相关<em>店铺</em>'+
				                    '</div>';

				        basicComboBox.on("afterCollapsedChange", function (e) {
				            var self = this;
				            if (!e.newVal) {
				                var menu = self.get('menu');
				                var menuEl = menu.get('el'), footer;
				                if (!(footer = menuEl.one(".s-combobox-menu-footer"))) {
				                    footer = new S.Node("<div class='s-combobox-menu-footer'></div>").appendTo(menuEl);
				                }
				                if (!footer.children().length) {
				                    footer.append(html);
				                }
				                footer.one(".tdg-input")
				                    .val(basicComboBox.get('input').val())
				            }
				        });

				        basicComboBox.on('afterRenderData', function () {
				            S.log('afterRenderData');
				        });

				        basicComboBox.on('click', function (e) {
				            S.log('search: ' + basicComboBox.get('value'));
				            //S.all('#search')[0].click();
				        });

				        S.all('#mallSearch').on('click', function () {
				            //location.hash = '#!q=' + basicComboBox.get('input').val();
				        });
				    })();
				});



			})
				
		</script>
		
		
		
		
		
		
	
		
		